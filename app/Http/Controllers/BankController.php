<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $q = Bank::query();
        if ($request->filled('search')) {
            $q->where('nama_bank', 'like', '%' . $request->search . '%');
        }
        $banks = $q->orderBy('nama_bank')->get();
        $total =  Bank::where('nama_bank', '!=', 'Virtual Bank')->sum('jumlah_total');
        return view('bank.index', compact('banks', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'jumlah_total' => 'required|string',
            'potongan_adm_bulanan' => 'nullable|string',
            'tgl_potongan_adm_bulanan' => 'nullable|integer|min:1|max:31',
        ]);
        Bank::create([
            'nama_bank' => $request->nama_bank,
            'jumlah_total' => parse_rupiah($request->jumlah_total),
            'potongan_adm_bulanan' => parse_rupiah($request->input('potongan_adm_bulanan', '0')),
            'tgl_potongan_adm_bulanan' => $request->tgl_potongan_adm_bulanan,
        ]);
        return back()->with('success', 'Bank berhasil ditambah.');
    }

    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'jumlah_total' => 'required|string',
            'potongan_adm_bulanan' => 'nullable|string',
            'tgl_potongan_adm_bulanan' => 'nullable|integer|min:1|max:31',
        ]);
        $bank->update([
            'nama_bank' => $request->nama_bank,
            'jumlah_total' => parse_rupiah($request->jumlah_total),
            'potongan_adm_bulanan' => parse_rupiah($request->input('potongan_adm_bulanan', '0')),
            'tgl_potongan_adm_bulanan' => $request->tgl_potongan_adm_bulanan,
        ]);
        return back()->with('success', 'Bank berhasil diupdate.');
    }

    public function prosesAdm()
    {
        $result = \App\Services\BankAdminFeeService::process(now(), true);

        return back()->with(
            'success',
            "Proses admin selesai: {$result['charged']} dipotong, {$result['skipped']} dilewati."
        );
    }

    public function destroy(Bank $bank)
    {
        if ($bank->keuangans()->exists()) {
            return back()->withErrors(['error' => 'Bank masih dipakai di data keuangan, tidak bisa dihapus.']);
        }
        if ($bank->id && (\App\Models\TfAntarBank::where('from_id_bank', $bank->id)->orWhere('to_id_bank', $bank->id)->exists())) {
            return back()->withErrors(['error' => 'Bank masih dipakai di TF antar bank.']);
        }
        $bank->delete();
        return back()->with('success', 'Bank dihapus.');
    }
}
