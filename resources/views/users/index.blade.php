@extends('layouts.app')
@section('title', 'User')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="m-0">User Terdaftar (role=user)</h5>
        <button class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addUser"><i class="bi bi-plus"></i> Tambah
            User</button>
    </div>
    <div class="card content-card p-3">
        <div class="table-responsive">
            <table class="table table-striped align-middle datatable" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>
                                @if ($u->photo_profile)
                                    <img src="{{ asset('storage/' . $u->photo_profile) }}" width="40" height="40"
                                    class="rounded-circle" style="object-fit:cover" alt="Foto {{ $u->name }}">@else<span
                                        class="badge text-bg-secondary"><i class="bi bi-person"></i></span>
                                @endif
                            </td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->username }}</td>
                            <td class="text-nowrap">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#eu{{ $u->id }}"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus user?')">@csrf @method('DELETE')<button
                                        class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                            </td>
                    </tr>@empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addUser" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">@csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Tambah User</h6><button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-12"><label class="form-label">Nama</label><input name="name"
                                    class="form-control" required></div>
                            <div class="col-6"><label class="form-label">Username</label><input name="username"
                                    class="form-control" required></div>
                            <div class="col-6"><label class="form-label">Password</label><input name="password"
                                    type="password" class="form-control" required></div>
                            <div class="col-12"><label class="form-label">Foto</label><input type="file"
                                    name="photo_profile" class="form-control" accept="image/*"></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal"
                            type="button">Batal</button><button class="btn btn-purple">Simpan</button></div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($users as $u)
        <div class="modal fade" id="eu{{ $u->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('users.update', $u) }}" enctype="multipart/form-data">@csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h6 class="modal-title">Edit User</h6><button class="btn-close"
                                data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-12"><label class="form-label">Nama</label><input name="name"
                                        value="{{ $u->name }}" class="form-control" required></div>
                                <div class="col-6"><label class="form-label">Username</label><input name="username"
                                        value="{{ $u->username }}" class="form-control" required></div>
                                <div class="col-6"><label class="form-label">Password (kosongkan jika tidak
                                        ganti)</label><input name="password" type="password" class="form-control"></div>
                                <div class="col-12"><label class="form-label">Foto</label><input type="file"
                                        name="photo_profile" class="form-control" accept="image/*"></div>
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
