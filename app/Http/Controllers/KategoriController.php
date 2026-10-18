<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $q = Kategori::query();
        if ($request->filled('tipe')) $q->where('tipe', $request->tipe);
        if ($request->filled('search')) $q->where('nama_kategori', 'like', '%' . $request->search . '%');
        $kategoris = $q->orderBy('nama_kategori')->get();
        return view('kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'tipe' => 'required|in:Masuk,Keluar,Semua',
        ]);
        Kategori::create($request->only('nama_kategori', 'tipe'));
        return back()->with('success', 'Kategori ditambah.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'tipe' => 'required|in:Masuk,Keluar,Semua',
        ]);
        $kategori->update($request->only('nama_kategori', 'tipe'));
        return back()->with('success', 'Kategori diupdate.');
    }

    public function destroy(Kategori $kategori)
    {
        if (\App\Models\Keuangan::where('id_kategori', $kategori->id)->exists()) {
            return back()->withErrors(['error' => 'Kategori masih dipakai.']);
        }
        $kategori->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}
