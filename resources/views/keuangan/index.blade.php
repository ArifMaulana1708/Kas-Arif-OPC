@extends('layouts.app')
@section('title', $status)
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="m-0">
            {{ $status }} - Index
            <small class="text-muted">
                {{-- ({{ $start }} s/d {{ $end }}) --}}
            </small>
        </h5>
        @if (auth()->user()->isAdmin())
            <button class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal"><i
                    class="bi bi-plus"></i> Tambah</button>
        @endif
    </div>

    @livewire('keuangan-table', ['status' => $status])

    @if (auth()->user()->isAdmin())
        <div class="modal fade" id="tambahModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('masuk.store') }}" enctype="multipart/form-data">@csrf
                        <input type="hidden" name="status" value="{{ $status }}">
                        <div class="modal-header">
                            <h6 class="modal-title">Tambah {{ $status }}</h6><button class="btn-close"
                                data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-6"><label class="form-label">Tanggal</label><input type="date"
                                        name="tanggal_keuangan" value="{{ date('Y-m-d') }}" class="form-control" required>
                                </div>
                                <div class="col-6"><label class="form-label">Jumlah (Rp)</label><input name="jumlah"
                                        class="form-control rupiah-input" placeholder="1.000.000" required
                                        inputmode="numeric"></div>
                                <div class="col-6"><label class="form-label">Bank</label><select name="id_bank"
                                        class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach ($banks as $b)
                                            <option value="{{ $b->id }}">{{ $b->nama_bank }}
                                                ({{ rupiah($b->jumlah_total) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6"><label class="form-label">Kategori</label><select name="id_kategori"
                                        class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach ($kategoris as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                        @endforeach
                                    </select></div>
                                <div class="col-12"><label class="form-label">Catatan</label><input name="catatan"
                                        class="form-control" placeholder="cth: Gaji / Belanja"></div>
                                <div class="col-12"><label class="form-label">Foto Bukti (opsional)</label><input
                                        type="file" name="foto_bukti" class="form-control" accept="image/*"
                                        capture="environment"></div>
                            </div>
                        </div>
                        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal"
                                type="button">Batal</button><button class="btn btn-purple">Simpan</button></div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
