@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card content-card p-4 text-center">
                @if ($user->photo_profile)
                    <img src="{{ asset('storage/' . $user->photo_profile) }}" width="120" height="120"
                        class="rounded-circle border mx-auto mb-3" style="object-fit:cover" alt="Foto profil">
                @else
                    <span class="badge text-bg-secondary rounded-circle p-4 mx-auto mb-3"><i
                            class="bi bi-person fs-1"></i></span>
                @endif
                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                <div class="text-muted small mb-3">{{ $user->username }} • {{ $user->role }}</div>

                <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" class="text-start">
                    @csrf @method('PUT')
                    <div class="mb-2"><label class="form-label">Nama</label><input name="name"
                            value="{{ old('name', $user->name) }}" class="form-control" required></div>
                    <div class="mb-2"><label class="form-label">Username</label><input name="username"
                            value="{{ old('username', $user->username) }}" class="form-control" required></div>
                    <div class="row g-2">
                        <div class="col-6"><label class="form-label">PW Baru (Opsional)</label><input name="password"
                                type="password" class="form-control" placeholder="Kosongkan jika tidak ganti"></div>
                        <div class="col-6"><label class="form-label">Konfirmasi PW</label><input
                                name="password_confirmation" type="password" class="form-control"></div>
                    </div>
                    <div class="mt-2"><label class="form-label">Foto Profil (opsional, max 2MB)</label><input
                            name="photo_profile" type="file" class="form-control" accept="image/*"></div>
                    @if ($user->photo_profile)
                        <div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="hapus_foto"
                                value="1" id="hapusFoto"><label class="form-check-label" for="hapusFoto">Hapus foto
                                profil</label></div>
                    @endif
                    <button class="btn btn-purple w-100 mt-3">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
