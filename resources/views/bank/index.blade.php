@extends('layouts.app')
@section('title', 'Bank')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="m-0">Saldo Bank & E-Wallet <span class="badge text-bg-success">Total : {{ rupiah($total) }}</span></h5>
        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('bank.proses-adm') }}"
                onsubmit="return confirm('Proses potongan admin yang jatuh tempo & belum dipotong bulan ini?')">@csrf
                <button class="btn btn-outline-primary btn-sm"><i class="bi bi-lightning"></i> Proses Adm
                    Sekarang</button>
            </form>
            <button class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addBank"><i
                    class="bi bi-plus"></i>
                Tambah</button>
        </div>
    </div>
    <div class="card content-card p-3">
        <div class="table-responsive">
            <table class="table table-striped align-middle datatable" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Nama Bank</th>
                        <th class="text-end">Saldo</th>
                        <th class="text-end">Adm/bln</th>
                        <th>Tgl Potong</th>
                        <th>Terakhir Dipotong</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banks as $b)
                        <tr>
                            <td class="fw-bold">{{ $b->nama_bank }}</td>
                            <td class="text-end" data-order="{{ $b->jumlah_total }}">{{ rupiah($b->jumlah_total) }}</td>
                            <td class="text-end" data-order="{{ $b->potongan_adm_bulanan ?? 0 }}">
                                {{ ($b->potongan_adm_bulanan ?? 0) > 0 ? rupiah($b->potongan_adm_bulanan) : '-' }}</td>
                            <td>{{ $b->tgl_potongan_adm_bulanan ?? '-' }}</td>
                            <td>{{ $b->last_adm_periode ?? '-' }}</td>
                            <td class="text-nowrap">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $b->id }}"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('bank.destroy', $b) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus bank ini?')">@csrf @method('DELETE')<button
                                        class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                            </td>
                    </tr>@empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addBank" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('bank.store') }}">@csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Tambah Bank</h6><button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Nama Bank / E-Wallet</label><input name="nama_bank"
                            class="form-control mb-2" placeholder="BCA / DANA / Cash" required>
                        <label class="form-label">Saldo Awal (Rp)</label><input name="jumlah_total"
                            class="form-control rupiah-input mb-2" placeholder="0" required inputmode="numeric">
                        <div class="row g-2">
                            <div class="col-7"><label class="form-label">Potongan Adm/bln (Rp, 0 =
                                    nonaktif)</label><input name="potongan_adm_bulanan"
                                    class="form-control rupiah-input" placeholder="0" value="0"
                                    inputmode="numeric"></div>
                            <div class="col-5"><label class="form-label">Tgl Potong (1-31)</label><input
                                    name="tgl_potongan_adm_bulanan" type="number" min="1" max="31"
                                    class="form-control" placeholder="cth: 1"></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal"
                            type="button">Batal</button><button class="btn btn-purple">Simpan</button></div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($banks as $b)
        <div class="modal fade" id="edit{{ $b->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('bank.update', $b) }}">@csrf @method('PUT')
                        <div class="modal-header">
                            <h6 class="modal-title">Edit Bank</h6><button class="btn-close"
                                data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Nama Bank</label><input name="nama_bank" value="{{ $b->nama_bank }}"
                                class="form-control mb-2" required>
                            <label class="form-label">Saldo (Rp)</label><input name="jumlah_total"
                                value="{{ number_format($b->jumlah_total, 0, ',', '.') }}"
                                class="form-control rupiah-input mb-2" required>
                            <div class="row g-2">
                                <div class="col-7"><label class="form-label">Potongan Adm/bln (Rp, 0 =
                                        nonaktif)</label><input name="potongan_adm_bulanan"
                                        value="{{ number_format($b->potongan_adm_bulanan ?? 0, 0, ',', '.') }}"
                                        class="form-control rupiah-input" inputmode="numeric"></div>
                                <div class="col-5"><label class="form-label">Tgl Potong (1-31)</label><input
                                        name="tgl_potongan_adm_bulanan" type="number" min="1" max="31"
                                        value="{{ $b->tgl_potongan_adm_bulanan }}" class="form-control"
                                        placeholder="cth: 1"></div>
                            </div>
                            @if ($b->last_adm_periode)
                                <small class="text-muted d-block mt-2">Terakhir dipotong: {{ $b->last_adm_periode }}</small>
                            @endif
                            <small class="text-muted">Hati-hati: mengubah saldo manual tidak mencatat mutasi.</small>
                        </div>
                        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal"
                                type="button">Batal</button><button class="btn btn-purple">Simpan</button></div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
