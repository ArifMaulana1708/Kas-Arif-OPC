<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isAdmin ? 'Register Admin' : 'Register User' }} - Kas Arif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <style>
        body {
            background: linear-gradient(135deg, #b66dff, #9a55ff);
            min-height: 100vh
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center p-3">
    <div class="card shadow p-4" style="max-width:440px;width:100%;border-radius:16px">
        <h4 class="fw-bold text-center">{{ $isAdmin ? 'Daftar Admin' : 'Daftar User' }}</h4>
        <img src="{{ asset('img/NovoResume.jpeg') }}" width="120" height="120"
            class="rounded-circle border mx-auto mb-3" style="object-fit:cover" alt="Foto profil">
        @if ($errors->any())
            <div class="alert alert-danger py-2">
                <ul class="m-0 small">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ $isAdmin ? route('adm_regist.post') : route('register.post') }}"
            enctype="multipart/form-data">@csrf
            <div class="mb-2"><label class="form-label">Nama</label><input name="name" class="form-control"
                    value="{{ old('name') }}" required></div>
            <div class="mb-2"><label class="form-label">Username</label><input name="username" class="form-control"
                    value="{{ old('username') }}" required></div>
            <div class="mb-2"><label class="form-label">Password</label><input name="password" type="password"
                    class="form-control" required></div>
            <div class="mb-2"><label class="form-label">Konfirmasi Password</label><input name="password_confirmation"
                    type="password" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Foto Profil (opsional)</label><input name="photo_profile"
                    type="file" class="form-control" accept="image/*"></div>
            <button class="btn btn-primary w-100" style="background:#9a55ff;border:0">Daftar</button>
        </form>
        <div class="text-center mt-3 small"><a href="{{ route('login') }}">Kembali ke Login</a></div>
    </div>
</body>

</html>
