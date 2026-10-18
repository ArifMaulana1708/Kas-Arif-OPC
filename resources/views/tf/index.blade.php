@extends('layouts.app')
@section('title', 'TF Antar Bank')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="m-0">TF Antar Bank <small class="text-muted">({{ $start }} s/d {{ $end }})</small>
            <span class="badge text-bg-primary">TF: {{ rupiah($total) }}</span>
            <span class="badge text-bg-warning">Admin: {{ rupiah($totalAdmin ?? 0) }}</span>
        </h5>
        <button class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addTf"><i class="bi bi-plus"></i>
            Transfer</button>
    </div>

    <div class="card content-card p-3 mb-3">
        <form method="GET" class="row g-2">
            <div class="col-6 col-md-3"><label class="form-label small">Dari</label><input type="date" name="date_start"
                    value="{{ $start }}" class="form-control form-control-sm"></div>
            <div class="col-6 col-md-3"><label class="form-label small">Sampai</label><input type="date" name="date_end"
                    value="{{ $end }}" class="form-control form-control-sm"></div>
            <div class="col-8 col-md-4"><label class="form-label small">Cari</label><input name="search"
                    value="{{ request('search') }}" class="form-control form-control-sm" placeholder="remark..."></div>
            <div class="col-4 col-md-2 d-flex align-items-end"><button class="btn btn-purple btn-sm w-100">Filter</button>
            </div>
        </form>
    </div>

    <div class="card content-card p-3">
        <div class="table-responsive">
            <table class="table table-striped align-middle datatable" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Dari → Ke</th>
                        <th class="text-end">Jumlah TF</th>
                        <th class="text-end">Biaya Admin</th>
                        <th class="text-end">Total Bersih</th>
                        <th>Remark</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $r)
                        <tr>
                            <td class="text-nowrap" data-order="{{ $r->tanggal_tf->format('Y-m-d') }}">
                                {{ $r->tanggal_tf->format('d-m-Y') }}</td>
                            <td><span class="badge text-bg-primary">{{ $r->fromBank->nama_bank ?? '-' }}</span> → <span
                                    class="badge text-bg-success">{{ $r->toBank->nama_bank ?? '-' }}</span></td>
                            <td class="text-end fw-bold" data-order="{{ $r->jumlah_tf }}">{{ rupiah($r->jumlah_tf) }}</td>
                            <td class="text-end" data-order="{{ $r->uang_admin ?? 0 }}">{{ rupiah($r->uang_admin ?? 0) }}
                            </td>
                            <td class="text-end fw-bold text-danger"
                                data-order="{{ $r->jumlah_tf - ($r->uang_admin ?? 0) }}">
                                {{ rupiah($r->jumlah_tf - ($r->uang_admin ?? 0)) }}</td>
                            <td class="small">{{ $r->remark }}</td>
                            <td>
                                @if ($r->foto_bukti)
                                    <a href="{{ asset('storage/' . $r->foto_bukti) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary"><i class="bi bi-image"></i></a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-nowrap">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#et{{ $r->id }}"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('tf.destroy', $r) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus? Saldo dikembalikan.')">@csrf @method('DELETE')<button
                                        class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                            </td>
                    </tr>@empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addTf" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('tf.store') }}" enctype="multipart/form-data">@csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Transfer Antar Bank</h6><button class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-6"><label class="form-label">Dari Bank</label><select name="from_id_bank"
                                    class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach ($banks as $b)
                                        <option value="{{ $b->id }}">{{ $b->nama_bank }}
                                            ({{ rupiah($b->jumlah_total) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6"><label class="form-label">Ke Bank</label><select name="to_id_bank"
                                    class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach ($banks as $b)
                                        <option value="{{ $b->id }}">{{ $b->nama_bank }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6"><label class="form-label">Tanggal</label><input type="date"
                                    name="tanggal_tf" value="{{ date('Y-m-d') }}" class="form-control" required></div>
                            <div class="col-6"><label class="form-label">Jumlah TF (Rp)</label><input name="jumlah_tf"
                                    class="form-control rupiah-input" placeholder="500.000" required inputmode="numeric">
                            </div>
                            <div class="col-12"><label class="form-label">Uang Admin (Rp, opsional)</label><input
                                    name="uang_admin" class="form-control rupiah-input"
                                    placeholder="0 — kosongkan jika tarik tunai / gratis" inputmode="numeric">
                                <small class="text-muted">Bank asal kepotong Jumlah + Admin, bank tujuan terima
                                    bersih.</small>
                            </div>
                            <div class="col-12"><label class="form-label">Remark</label><input name="remark"
                                    class="form-control"></div>
                            <div class="col-12"><label class="form-label">Foto Bukti (opsional)</label><input
                                    type="file" name="foto_bukti" class="form-control" accept="image/*"></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal"
                            type="button">Batal</button><button class="btn btn-purple">Transfer</button></div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($data as $r)
        <div class="modal fade" id="et{{ $r->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('tf.update', $r) }}" enctype="multipart/form-data">@csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h6 class="modal-title">Edit Transfer</h6><button class="btn-close"
                                data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-6"><label class="form-label">Dari</label><select name="from_id_bank"
                                        class="form-select">
                                        @foreach ($banks as $b)
                                            <option value="{{ $b->id }}"
                                                {{ $r->from_id_bank == $b->id ? 'selected' : '' }}>{{ $b->nama_bank }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6"><label class="form-label">Ke</label><select name="to_id_bank"
                                        class="form-select">
                                        @foreach ($banks as $b)
                                            <option value="{{ $b->id }}"
                                                {{ $r->to_id_bank == $b->id ? 'selected' : '' }}>{{ $b->nama_bank }}
                                            </option>
                                        @endforeach
                                    </select></div>
                                <div class="col-6"><label class="form-label">Tanggal</label><input type="date"
                                        name="tanggal_tf" value="{{ $r->tanggal_tf->format('Y-m-d') }}"
                                        class="form-control"></div>
                                <div class="col-6"><label class="form-label">Jumlah TF</label><input name="jumlah_tf"
                                        value="{{ number_format($r->jumlah_tf, 0, ',', '.') }}"
                                        class="form-control rupiah-input"></div>
                                <div class="col-12"><label class="form-label">Uang Admin (opsional)</label><input
                                        name="uang_admin" value="{{ number_format($r->uang_admin ?? 0, 0, ',', '.') }}"
                                        class="form-control rupiah-input"></div>
                                <div class="col-12"><label class="form-label">Remark</label><input name="remark"
                                        value="{{ $r->remark }}" class="form-control"></div>
                                <div class="col-12"><label class="form-label">Foto (opsional)</label><input
                                        type="file" name="foto_bukti" class="form-control" accept="image/*"></div>
                            </div>
                        </div>
                        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal"
                                type="button">Batal</button><button class="btn btn-purple">Simpan</button></div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
