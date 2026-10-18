<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\TfAntarBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TfAntarBankController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('date_start', now()->startOfMonth()->toDateString());
        $end = $request->input('date_end', now()->endOfMonth()->toDateString());
        $q = TfAntarBank::with(['fromBank', 'toBank'])->whereBetween('tanggal_tf', [$start, $end]);
        if ($request->filled('search')) $q->where('remark', 'like', '%' . $request->search . '%');
        // DataTables: ambil semua, pagination di client
        $data = $q->orderBy('tanggal_tf', 'desc')->get();
        $total = (clone $q)->sum('jumlah_tf');
        $totalAdmin = (clone $q)->sum('uang_admin');
        $banks = Bank::orderBy('nama_bank')->get();
        return view('tf.index', compact('data', 'total', 'totalAdmin', 'banks', 'start', 'end'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_id_bank' => 'required|exists:banks,id',
            'to_id_bank' => 'required|exists:banks,id|different:from_id_bank',
            'tanggal_tf' => 'required|date',
            'jumlah_tf' => 'required|string',
            'uang_admin' => 'nullable|string',
            'remark' => 'nullable|string',
            'foto_bukti' => 'nullable|image|max:3072',
        ]);
        $jumlah = parse_rupiah($request->jumlah_tf);
        $admin = parse_rupiah($request->input('uang_admin', '0'));
        if ($jumlah < 1) return back()->withErrors(['jumlah_tf' => 'Jumlah tidak valid.']);

        $totalPotong = $jumlah + $admin;
        $from = Bank::find($request->from_id_bank);
        if ($from->jumlah_total < $totalPotong) {
            return back()->withErrors(['jumlah_tf' => 'Saldo bank asal tidak cukup. Butuh ' . rupiah($totalPotong) . ', saldo ' . rupiah($from->jumlah_total) . '.']);
        }

        DB::transaction(function () use ($request, $jumlah, $admin) {
            $foto = $request->hasFile('foto_bukti')
                ? $request->file('foto_bukti')->store('bukti_tf', 'public') : null;
            TfAntarBank::create([
                'from_id_bank' => $request->from_id_bank,
                'to_id_bank' => $request->to_id_bank,
                'tanggal_tf' => $request->tanggal_tf,
                'jumlah_tf' => $jumlah,
                'uang_admin' => $admin,
                'remark' => $request->remark,
                'foto_bukti' => $foto,
            ]);
            // bank asal kepotong jumlah + admin, bank tujuan terima bersih
            Bank::lockForUpdate()->find($request->from_id_bank)->decrement('jumlah_total', $jumlah + $admin);
            Bank::lockForUpdate()->find($request->to_id_bank)->increment('jumlah_total', $jumlah);
        });

        return back()->with('success', 'Transfer antar bank berhasil.');
    }

    public function update(Request $request, TfAntarBank $tfAntarBank)
    {
        $request->validate([
            'from_id_bank' => 'required|exists:banks,id',
            'to_id_bank' => 'required|exists:banks,id|different:from_id_bank',
            'tanggal_tf' => 'required|date',
            'jumlah_tf' => 'required|string',
            'uang_admin' => 'nullable|string',
            'remark' => 'nullable|string',
            'foto_bukti' => 'nullable|image|max:3072',
        ]);
        $jumlahBaru = parse_rupiah($request->jumlah_tf);
        $adminBaru = parse_rupiah($request->input('uang_admin', '0'));
        if ($jumlahBaru < 1) return back()->withErrors(['jumlah_tf' => 'Jumlah tidak valid.']);

        try {
            DB::transaction(function () use ($request, $tfAntarBank, $jumlahBaru, $adminBaru) {
                // revert lama: kembalikan jumlah+admin ke asal, tarik jumlah dari tujuan
                $oldAdmin = (int) ($tfAntarBank->uang_admin ?? 0);
                Bank::lockForUpdate()->find($tfAntarBank->from_id_bank)->increment('jumlah_total', $tfAntarBank->jumlah_tf + $oldAdmin);
                Bank::lockForUpdate()->find($tfAntarBank->to_id_bank)->decrement('jumlah_total', $tfAntarBank->jumlah_tf);

                $from = Bank::lockForUpdate()->find($request->from_id_bank);
                if ($from->jumlah_total < ($jumlahBaru + $adminBaru)) {
                    throw new \Exception('Saldo bank asal tidak cukup. Butuh ' . rupiah($jumlahBaru + $adminBaru) . ', saldo ' . rupiah($from->jumlah_total) . '.');
                }

                $foto = $tfAntarBank->foto_bukti;
                if ($request->hasFile('foto_bukti')) {
                    if ($foto) Storage::disk('public')->delete($foto);
                    $foto = $request->file('foto_bukti')->store('bukti_tf', 'public');
                }

                $tfAntarBank->update([
                    'from_id_bank' => $request->from_id_bank,
                    'to_id_bank' => $request->to_id_bank,
                    'tanggal_tf' => $request->tanggal_tf,
                    'jumlah_tf' => $jumlahBaru,
                    'uang_admin' => $adminBaru,
                    'remark' => $request->remark,
                    'foto_bukti' => $foto,
                ]);

                Bank::lockForUpdate()->find($request->from_id_bank)->decrement('jumlah_total', $jumlahBaru + $adminBaru);
                Bank::lockForUpdate()->find($request->to_id_bank)->increment('jumlah_total', $jumlahBaru);
            });
        } catch (\Exception $e) {
            return back()->withErrors(['jumlah_tf' => $e->getMessage()]);
        }

        return back()->with('success', 'Transfer berhasil diupdate.');
    }

    public function destroy(TfAntarBank $tfAntarBank)
    {
        DB::transaction(function () use ($tfAntarBank) {
            $oldAdmin = (int) ($tfAntarBank->uang_admin ?? 0);
            Bank::lockForUpdate()->find($tfAntarBank->from_id_bank)->increment('jumlah_total', $tfAntarBank->jumlah_tf + $oldAdmin);
            Bank::lockForUpdate()->find($tfAntarBank->to_id_bank)->decrement('jumlah_total', $tfAntarBank->jumlah_tf);
            if ($tfAntarBank->foto_bukti) Storage::disk('public')->delete($tfAntarBank->foto_bukti);
            $tfAntarBank->delete();
        });
        return back()->with('success', 'Transfer dihapus & saldo dikembalikan.');
    }
}
