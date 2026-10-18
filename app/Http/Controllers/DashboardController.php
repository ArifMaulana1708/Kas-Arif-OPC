<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Catch-up potongan admin: kalau scheduler kelewat (misal laptop/XAMPP
        // mati jam 00:05), kekurangan bulan berjalan dikejar saat dashboard dibuka.
        // Idempotent via last_adm_periode, jadi aman dipanggil tiap request.
        try {
            \App\Services\BankAdminFeeService::process(now());
        } catch (\Throwable) {
            // Jangan ganggu dashboard kalau proses adm gagal.
        }

        $bulan = $request->input('bulan', now()->format('Y-m'));
        try {
            $date = \Carbon\Carbon::createFromFormat('Y-m', $bulan);
        } catch (\Exception $e) {
            $date = now();
            $bulan = $date->format('Y-m');
        }
        $start = $date->copy()->startOfMonth()->toDateString();
        $end = $date->copy()->endOfMonth()->toDateString();

        $masuk = Keuangan::where('status', 'Uang Masuk')->whereBetween('tanggal_keuangan', [$start, $end])->sum('jumlah');
        $keluar = Keuangan::where('status', 'Uang Keluar')->whereBetween('tanggal_keuangan', [$start, $end])->sum('jumlah');
        $balance = $masuk - $keluar;
        $totalSaldo = Bank::where('nama_bank', '!=', 'Virtual Bank')->sum('jumlah_total');

        // grafik harian
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

        // donut per kategori keluar
        $perKategori = Keuangan::with('kategori')
            ->selectRaw('id_kategori, SUM(jumlah) as total')
            ->where('status', 'Uang Keluar')
            ->whereBetween('tanggal_keuangan', [$start, $end])
            ->groupBy('id_kategori')
            ->get();

        return view('dashboard', compact('bulan', 'masuk', 'keluar', 'balance', 'totalSaldo', 'labels', 'dataMasuk', 'dataKeluar', 'perKategori', 'start', 'end'));
    }
}
