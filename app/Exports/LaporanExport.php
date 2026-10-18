<?php

namespace App\Exports;

use App\Models\Keuangan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanExport implements FromCollection, WithHeadings
{
    public function __construct(public string $start, public string $end, public string $status = 'Semua') {}

    public function collection()
    {
        $q = Keuangan::with(['bank', 'kategori'])->whereBetween('tanggal_keuangan', [$this->start, $this->end]);
        if (in_array($this->status, ['Uang Masuk', 'Uang Keluar'])) $q->where('status', $this->status);
        return $q->orderBy('tanggal_keuangan')->get()->map(function ($r, $i) {
            return [
                'No' => $i + 1,
                'Tanggal' => $r->tanggal_keuangan->format('d-m-Y'),
                'Status' => $r->status,
                'Bank' => $r->bank->nama_bank ?? '-',
                'Kategori' => $r->kategori->nama_kategori ?? '-',
                'Jumlah' => $r->jumlah,
                'Catatan' => $r->catatan,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Tanggal', 'Status', 'Bank', 'Kategori', 'Jumlah', 'Catatan'];
    }
}
