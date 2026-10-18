<?php

namespace App\Livewire;

use App\Models\Bank;
use App\Models\Kategori;
use App\Models\Keuangan;
use Livewire\Component;

class KeuanganTable extends Component
{
    public string $status = 'Uang Masuk';
    public string $date_start;
    public string $date_end;
    public string $search = '';
    public string $id_kategori = '';
    public string $id_bank = '';

    protected $queryString = ['search', 'date_start', 'date_end'];

    public function mount(string $status): void
    {
        $this->status = $status;
        $this->date_start = now()->startOfMonth()->toDateString();
        $this->date_end = now()->endOfMonth()->toDateString();
    }

    public function render()
    {
        $q = Keuangan::with(['bank', 'kategori'])
            ->where('status', $this->status)
            ->whereBetween('tanggal_keuangan', [$this->date_start, $this->date_end]);
        if ($this->id_kategori !== '') $q->where('id_kategori', $this->id_kategori);
        if ($this->id_bank !== '' && auth()->user()->isAdmin()) $q->where('id_bank', $this->id_bank);
        if ($this->search !== '') $q->where('catatan', 'like', '%' . $this->search . '%');
        // DataTables handle pagination/sort di client
        $data = $q->orderBy('tanggal_keuangan', 'desc')->get();
        $total = (clone $q)->sum('jumlah');

        $isAdmin = auth()->user()->isAdmin();
        $banks = $isAdmin ? Bank::orderBy('nama_bank')->get() : collect();
        $tipe = $this->status === 'Uang Masuk' ? 'Masuk' : 'Keluar';
        $kategoris = Kategori::whereIn('tipe', [$tipe, 'Semua'])->orderBy('nama_kategori')->get();

        return view('livewire.keuangan-table', compact('data', 'total', 'banks', 'kategoris', 'isAdmin'));
    }
}
