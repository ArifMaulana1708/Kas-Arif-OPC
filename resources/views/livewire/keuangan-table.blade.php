<div>
    <div class="card content-card p-3 mb-3">
        <div class="row g-2">
            <div class="col-6 col-md-2"><label class="form-label small">Dari</label><input type="date"
                    wire:model.live="date_start" class="form-control form-control-sm"></div>
            <div class="col-6 col-md-2"><label class="form-label small">Sampai</label><input type="date"
                    wire:model.live="date_end" class="form-control form-control-sm"></div>
            <div class="col-6 col-md-2"><label class="form-label small">Kategori</label>
                <select wire:model.live="id_kategori" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            @if ($isAdmin)
                <div class="col-6 col-md-2"><label class="form-label small">Bank</label>
                    <select wire:model.live="id_bank" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach ($banks as $b)
                            <option value="{{ $b->id }}">{{ $b->nama_bank }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="col-12 col-md-4"><label class="form-label small">Cari Catatan (filter server)</label><input
                    wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Cari...">
            </div>
        </div>
        <div class="mt-2 small fw-bold">Total {{ $status }}: {{ rupiah($total) }} ({{ $data->count() }} transaksi)
        </div>
    </div>

    <div class="card content-card p-3">
        <div class="table-responsive">
            <table class="table datatable table-striped align-middle" id="keuTable-{{ md5($status) }}"
                style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        @if ($isAdmin)
                            <th>Bank</th>
                        @endif
                        <th>Kategori</th>
                        <th class="text-end">Jumlah</th>
                        <th>Catatan</th>
                        @if ($isAdmin)
                            <th>Bukti</th>
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $r)
                        <tr>
                            <td class="text-nowrap" data-order="{{ $r->tanggal_keuangan->format('Y-m-d') }}">
                                {{ $r->tanggal_keuangan->format('d-m-Y') }}</td>
                            @if ($isAdmin)
                                <td>{{ $r->bank->nama_bank ?? '-' }}</td>
                            @endif
                            <td>{{ $r->kategori->nama_kategori ?? '-' }}</td>
                            <td class="text-end fw-bold" data-order="{{ $r->jumlah }}">{{ rupiah($r->jumlah) }}
                            </td>
                            <td class="small">{{ $r->catatan }}</td>
                            @if ($isAdmin)
                                <td>
                                    @if ($r->foto_bukti)
                                        <a href="{{ asset('storage/' . $r->foto_bukti) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary"><i
                                            class="bi bi-image"></i></a>@else<span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editKeu{{ $r->id }}"><i
                                            class="bi bi-pencil"></i></button>
                                    <form action="{{ route('keuangan.destroy', $r) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus? Saldo akan dikembalikan.')">@csrf
                                        @method('DELETE')<button class="btn btn-sm btn-danger"><i
                                                class="bi bi-trash"></i></button></form>
                                </td>
                            @endif
                        </tr>
                    @empty

                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($isAdmin)
        @foreach ($data as $r)
            <div class="modal fade" id="editKeu{{ $r->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('keuangan.update', $r) }}" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="modal-header">
                                <h6 class="modal-title">{{ $status }} - Edit</h6><button class="btn-close"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-2">
                                    <div class="col-6"><label class="form-label">Tanggal</label><input type="date"
                                            name="tanggal_keuangan" value="{{ $r->tanggal_keuangan->format('Y-m-d') }}"
                                            class="form-control" required></div>
                                    <div class="col-6"><label class="form-label">Jumlah (Rp)</label><input
                                            name="jumlah" class="form-control rupiah-input"
                                            value="{{ number_format($r->jumlah, 0, ',', '.') }}" required></div>
                                    <div class="col-6"><label class="form-label">Bank</label><select name="id_bank"
                                            class="form-select">
                                            @foreach ($banks as $b)
                                                <option value="{{ $b->id }}"
                                                    {{ $r->id_bank == $b->id ? 'selected' : '' }}>{{ $b->nama_bank }}
                                                    ({{ rupiah($b->jumlah_total) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6"><label class="form-label">Kategori</label><select
                                            name="id_kategori" class="form-select">
                                            @foreach ($kategoris as $k)
                                                <option value="{{ $k->id }}"
                                                    {{ $r->id_kategori == $k->id ? 'selected' : '' }}>
                                                    {{ $k->nama_kategori }}</option>
                                            @endforeach
                                        </select></div>
                                    <div class="col-12"><label class="form-label">Catatan</label><input name="catatan"
                                            value="{{ $r->catatan }}" class="form-control"></div>
                                    <div class="col-12"><label class="form-label">Foto Bukti (opsional)</label><input
                                            type="file" name="foto_bukti" class="form-control" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal"
                                    type="button">Batal</button><button class="btn btn-purple">Simpan</button></div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
