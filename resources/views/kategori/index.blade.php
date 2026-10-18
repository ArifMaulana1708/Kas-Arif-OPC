@extends('layouts.app')
@section('title', 'Kategori')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="m-0">Kategori - Index</h5>
        <button class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addKat"><i class="bi bi-plus"></i>
            Tambah</button>
    </div>
    <div class="card content-card p-3">
        <div class="table-responsive">
            <table class="table table-striped align-middle datatable" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $k)
                        <tr>
                            <td>{{ $k->nama_kategori }}</td>
                            <td><span
                                    class="badge {{ $k->tipe == 'Masuk' ? 'text-bg-success' : ($k->tipe == 'Keluar' ? 'text-bg-danger' : 'text-bg-secondary') }}">{{ $k->tipe }}</span>
                            </td>
                            <td class="text-nowrap">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#ek{{ $k->id }}"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('kategori.destroy', $k) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button
                                        class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                            </td>
                    </tr>@empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addKat" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('kategori.store') }}">@csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Kategori - Tambah</h6><button class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Nama Kategori</label><input name="nama_kategori" class="form-control mb-2"
                            placeholder="Makanan / Gaji / Pangan Bulanan" required>
                        <label class="form-label">Tipe</label><select name="tipe" class="form-select">
                            <option>Masuk</option>
                            <option>Keluar</option>
                            <option selected>Semua</option>
                        </select>
                    </div>
                    <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal"
                            type="button">Batal</button><button class="btn btn-purple">Simpan</button></div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($kategoris as $k)
        <div class="modal fade" id="ek{{ $k->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('kategori.update', $k) }}">@csrf @method('PUT')
                        <div class="modal-header">
                            <h6 class="modal-title">Kategori - Edit</h6><button class="btn-close"
                                data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Nama</label><input name="nama_kategori"
                                value="{{ $k->nama_kategori }}" class="form-control mb-2" required>
                            <label class="form-label">Tipe</label><select name="tipe" class="form-select">
                                <option {{ $k->tipe == 'Masuk' ? 'selected' : '' }}>Masuk</option>
                                <option {{ $k->tipe == 'Keluar' ? 'selected' : '' }}>Keluar</option>
                                <option {{ $k->tipe == 'Semua' ? 'selected' : '' }}>Semua</option>
                            </select>
                        </div>
                        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal"
                                type="button">Batal</button><button class="btn btn-purple">Simpan</button></div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
