@extends('layouts.app')
@section('title', 'Laporan')
@section('content')
    <h5>
        Laporan Keuangan - Index</h5>
    <div class="card content-card p-3 mb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-6 col-md-3"><label class="form-label small">Dari</label><input type="date" name="date_start"
                    value="{{ $start }}" class="form-control form-control-sm"></div>
            <div class="col-6 col-md-3"><label class="form-label small">Sampai</label><input type="date" name="date_end"
                    value="{{ $end }}" class="form-control form-control-sm"></div>
            <div class="col-5 col-md-2"><label class="form-label small">Status</label><select name="status"
                    class="form-select form-select-sm">
                    <option {{ $status == 'Semua' ? 'selected' : '' }}>Semua</option>
                    <option {{ $status == 'Uang Masuk' ? 'selected' : '' }}>Uang Masuk</option>
                    <option {{ $status == 'Uang Keluar' ? 'selected' : '' }}>Uang Keluar</option>
                </select></div>
            <div class="col-7 col-md-4 d-flex gap-2">
                <button class="btn btn-purple btn-sm">Tampilkan</button>
                <a href="{{ route('laporan.excel', request()->all()) }}" class="btn btn-success btn-sm"><i
                        class="bi bi-file-excel"></i> Excel</a>
                <a href="{{ route('laporan.pdf', request()->all()) }}" class="btn btn-danger btn-sm"><i
                        class="bi bi-file-pdf"></i> PDF</a>
            </div>
        </form>
    </div>

    @if ($balance > 0)
        <div class="alert alert-success fw-bold">Balance: {{ rupiah($balance) }} (Surplus) | Masuk:
            {{ rupiah($totalMasuk) }} | Keluar: {{ rupiah($totalKeluar) }}</div>
    @elseif($balance < 0)
        <div class="alert alert-danger fw-bold">Balance: {{ rupiah($balance) }} (Minus) | Masuk: {{ rupiah($totalMasuk) }}
            | Keluar: {{ rupiah($totalKeluar) }}</div>
    @else
        <div class="alert alert-light border fw-bold">Balance: {{ rupiah($balance) }} (Pas) | Masuk:
            {{ rupiah($totalMasuk) }} | Keluar: {{ rupiah($totalKeluar) }}</div>
    @endif

    <div class="card content-card p-3">
        <div class="table-responsive">
            <table class="table table-striped align-middle datatable" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Tgl</th>
                        <th>Status</th>
                        <th>Bank</th>
                        <th>Kategori</th>
                        <th class="text-end">Jumlah</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $r)
                        <tr>
                            <td class="text-nowrap" data-order="{{ $r->tanggal_keuangan->format('Y-m-d') }}">
                                {{ $r->tanggal_keuangan->format('d-m-Y') }}</td>
                            <td><span
                                    class="badge {{ $r->status == 'Uang Masuk' ? 'text-bg-success' : 'text-bg-danger' }}">{{ $r->status }}</span>
                            </td>
                            <td>{{ $r->bank->nama_bank ?? '-' }}</td>
                            <td>{{ $r->kategori->nama_kategori ?? '-' }}</td>
                            <td class="text-end fw-bold" data-order="{{ $r->jumlah }}">{{ rupiah($r->jumlah) }}</td>
                            <td class="small">{{ $r->catatan }}</td>
                    </tr>@empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
