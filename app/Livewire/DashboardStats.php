<?php

namespace App\Livewire;

use App\Models\Bank;
use App\Models\Keuangan;
use Livewire\Component;

class DashboardStats extends Component
{
    public string $bulan;

    public function mount(): void
    {
        $this->bulan = now()->format('Y-m');
    }

    public function render()
    {
        try {
            $date = \Carbon\Carbon::createFromFormat('Y-m', $this->bulan);
        } catch (\Exception $e) {
            $date = now();
        }
        $start = $date->copy()->startOfMonth()->toDateString();
        $end = $date->copy()->endOfMonth()->toDateString();

        $masuk = Keuangan::where('status', 'Uang Masuk')->whereBetween('tanggal_keuangan', [$start, $end])->sum('jumlah');
        $keluar = Keuangan::where('status', 'Uang Keluar')->whereBetween('tanggal_keuangan', [$start, $end])->sum('jumlah');
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $totalSaldo = $isAdmin ?  Bank::where('nama_bank', '!=', 'Virtual Bank')->sum('jumlah_total') : 0;



        $days = $date->daysInMonth;
        $labels = [];
        $dataMasuk = [];
        $dataKeluar = [];
        for ($d = 1; $d <= $days; $d++) {
            $tgl = $date->copy()->day($d)->toDateString();
            $labels[] = (string) $d;
            $dataMasuk[] = (int) Keuangan::where('status', 'Uang Masuk')->whereDate('tanggal_keuangan', $tgl)->sum('jumlah');
            $dataKeluar[] = (int) Keuangan::where('status', 'Uang Keluar')->whereDate('tanggal_keuangan', $tgl)->sum('jumlah');
        }

        $perKategori = Keuangan::with('kategori')
            ->selectRaw('id_kategori, SUM(jumlah) as total')
            ->where('status', 'Uang Keluar')
            ->whereBetween('tanggal_keuangan', [$start, $end])
            ->groupBy('id_kategori')
            ->get();

        $this->dispatch(
            'dashboard-updated',
            labels: $labels,
            masuk: $dataMasuk,
            keluar: $dataKeluar,
            kategori: $perKategori->map(fn($x) => $x->kategori->nama_kategori ?? '-')->values(),
            total: $perKategori->pluck('total')->values(),
        );

        return view('livewire.dashboard-stats', compact(
            'masuk',
            'keluar',
            'totalSaldo',
            'labels',
            'dataMasuk',
            'dataKeluar',
            'perKategori',
            'start',
            'end'
        ));
    }
}
