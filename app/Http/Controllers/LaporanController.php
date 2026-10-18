<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Models\Keuangan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    private function query(Request $request)
    {
        $start = $request->input('date_start', now()->startOfMonth()->toDateString());
        $end = $request->input('date_end', now()->endOfMonth()->toDateString());
        $status = $request->input('status', 'Semua');
        $q = Keuangan::with(['bank', 'kategori'])->whereBetween('tanggal_keuangan', [$start, $end]);
        if (in_array($status, ['Uang Masuk', 'Uang Keluar'])) $q->where('status', $status);
        return [$q, $start, $end, $status];
    }

    public function index(Request $request)
    {
        [$q, $start, $end, $status] = $this->query($request);
        $data = (clone $q)->orderBy('tanggal_keuangan')->get();
        $totalMasuk = (clone $q)->where('status', 'Uang Masuk')->sum('jumlah');
        // hitung dari query dasar tanpa filter status ganda: pakai query fresh
        $base = Keuangan::whereBetween('tanggal_keuangan', [$start, $end]);
        $totalMasuk = (clone $base)->where('status', 'Uang Masuk')->sum('jumlah');
        $totalKeluar = (clone $base)->where('status', 'Uang Keluar')->sum('jumlah');
        $balance = $totalMasuk - $totalKeluar;
        return view('laporan.index', compact('data', 'start', 'end', 'status', 'totalMasuk', 'totalKeluar', 'balance'));
    }

    public function excel(Request $request)
    {
        [$q, $start, $end, $status] = $this->query($request);
        return Excel::download(new LaporanExport($start, $end, $status), "laporan-{$start}-{$end}.xlsx");
    }

    public function pdf(Request $request)
    {
        [$q, $start, $end, $status] = $this->query($request);
        $data = (clone $q)->orderBy('tanggal_keuangan')->get();
        $base = Keuangan::whereBetween('tanggal_keuangan', [$start, $end]);
        $totalMasuk = (clone $base)->where('status', 'Uang Masuk')->sum('jumlah');
        $totalKeluar = (clone $base)->where('status', 'Uang Keluar')->sum('jumlah');
        $balance = $totalMasuk - $totalKeluar;
        $pdf = Pdf::loadView('laporan.pdf', compact('data', 'start', 'end', 'status', 'totalMasuk', 'totalKeluar', 'balance'))
            ->setPaper('a4', 'landscape');
        return $pdf->download("laporan-{$start}-{$end}.pdf");
    }
}
