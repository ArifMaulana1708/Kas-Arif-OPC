<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Kategori;
use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KeuanganController extends Controller
{
    private function defaultRange(Request $request): array
    {
        $start = $request->input('date_start', now()->startOfMonth()->toDateString());
        $end = $request->input('date_end', now()->endOfMonth()->toDateString());
        return [$start, $end];
    }

    public function masuk(Request $request)
    {
        return $this->index($request, 'Uang Masuk');
    }

    public function keluar(Request $request)
    {
        return $this->index($request, 'Uang Keluar');
    }

    private function index(Request $request, string $status)
    {
        [$start, $end] = $this->defaultRange($request);
        $q = Keuangan::with(['bank', 'kategori'])
            ->where('status', $status)
            ->whereBetween('tanggal_keuangan', [$start, $end]);
        if ($request->filled('id_kategori')) $q->where('id_kategori', $request->id_kategori);
        if (auth()->user()->isAdmin() && $request->filled('id_bank')) $q->where('id_bank', $request->id_bank);
        if ($request->filled('search')) $q->where('catatan', 'like', '%' . $request->search . '%');
        $data = $q->orderBy('tanggal_keuangan', 'desc')->paginate(15)->withQueryString();
        $total = (clone $q)->sum('jumlah');

        $banks = auth()->user()->isAdmin() ? Bank::orderBy('nama_bank')->get() : collect();
        $kategoris = Kategori::whereIn('tipe', [$status === 'Uang Masuk' ? 'Masuk' : 'Keluar', 'Semua'])
            ->orderBy('nama_kategori')->get();

        $isMasuk = $status === 'Uang Masuk';
        return view('keuangan.index', compact('data', 'total', 'banks', 'kategoris', 'start', 'end', 'status', 'isMasuk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_bank' => 'required|exists:banks,id',
            'id_kategori' => 'required|exists:kategoris,id',
            'tanggal_keuangan' => 'required|date',
            'status' => 'required|in:Uang Masuk,Uang Keluar',
            'jumlah' => 'required|string',
            'catatan' => 'nullable|string',
            'foto_bukti' => 'nullable|image|max:3072',
        ]);
        $jumlah = parse_rupiah($request->jumlah);
        if ($jumlah < 1) return back()->withErrors(['jumlah' => 'Jumlah tidak valid.']);

        DB::transaction(function () use ($request, $jumlah) {
            $foto = null;
            if ($request->hasFile('foto_bukti')) {
                $foto = $request->file('foto_bukti')->store('bukti', 'public');
            }
            Keuangan::create([
                'id_bank' => $request->id_bank,
                'id_kategori' => $request->id_kategori,
                'tanggal_keuangan' => $request->tanggal_keuangan,
                'status' => $request->status,
                'jumlah' => $jumlah,
                'catatan' => $request->catatan,
                'foto_bukti' => $foto,
            ]);
            $bank = Bank::lockForUpdate()->find($request->id_bank);
            $bank->jumlah_total += ($request->status === 'Uang Masuk' ? $jumlah : -$jumlah);
            $bank->save();
        });

        return back()->with('success', 'Data berhasil disimpan & saldo bank diperbarui.');
    }

    public function update(Request $request, Keuangan $keuangan)
    {
        $request->validate([
            'id_bank' => 'required|exists:banks,id',
            'id_kategori' => 'required|exists:kategoris,id',
            'tanggal_keuangan' => 'required|date',
            'jumlah' => 'required|string',
            'catatan' => 'nullable|string',
            'foto_bukti' => 'nullable|image|max:3072',
        ]);
        $jumlahBaru = parse_rupiah($request->jumlah);
        if ($jumlahBaru < 1) return back()->withErrors(['jumlah' => 'Jumlah tidak valid.']);

        DB::transaction(function () use ($request, $keuangan, $jumlahBaru) {
            $oldBankId = $keuangan->id_bank;
            $oldJumlah = $keuangan->jumlah;
            $oldStatus = $keuangan->status;

            // revert saldo lama
            $oldBank = Bank::lockForUpdate()->find($oldBankId);
            $oldBank->jumlah_total += ($oldStatus === 'Uang Masuk' ? -$oldJumlah : $oldJumlah);
            $oldBank->save();

            $foto = $keuangan->foto_bukti;
            if ($request->hasFile('foto_bukti')) {
                if ($foto) Storage::disk('public')->delete($foto);
                $foto = $request->file('foto_bukti')->store('bukti', 'public');
            }

            $keuangan->update([
                'id_bank' => $request->id_bank,
                'id_kategori' => $request->id_kategori,
                'tanggal_keuangan' => $request->tanggal_keuangan,
                'jumlah' => $jumlahBaru,
                'catatan' => $request->catatan,
                'foto_bukti' => $foto,
            ]);

            // terapkan saldo baru
            $newBank = $oldBankId == $request->id_bank ? $oldBank : Bank::lockForUpdate()->find($request->id_bank);
            // jika beda bank, $oldBank sudah disimpan; ambil ulang newBank fresh
            if ($oldBankId != $request->id_bank) {
                $newBank = Bank::lockForUpdate()->find($request->id_bank);
            } else {
                $newBank = Bank::lockForUpdate()->find($request->id_bank);
            }
            $newBank->jumlah_total += ($keuangan->status === 'Uang Masuk' ? $jumlahBaru : -$jumlahBaru);
            $newBank->save();
        });

        return back()->with('success', 'Data berhasil diupdate.');
    }

    public function destroy(Keuangan $keuangan)
    {
        DB::transaction(function () use ($keuangan) {
            $bank = Bank::lockForUpdate()->find($keuangan->id_bank);
            $bank->jumlah_total += ($keuangan->status === 'Uang Masuk' ? -$keuangan->jumlah : $keuangan->jumlah);
            $bank->save();
            if ($keuangan->foto_bukti) Storage::disk('public')->delete($keuangan->foto_bukti);
            $keuangan->delete();
        });
        return back()->with('success', 'Data dihapus & saldo dikembalikan.');
    }
}
