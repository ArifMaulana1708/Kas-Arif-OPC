<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Kas Arif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #b66dff, #9a55ff);
            min-height: 100vh
        }

        .card {
            border-radius: 16px
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center p-3">
    <div class="card shadow p-4" style="max-width:400px;width:100%">
        <h4 class="fw-bold text-center mb-1">💰 Kas Arif</h4>
        <p class="text-muted text-center small">Silakan login</p>
        @if (session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger py-2">
                <ul class="m-0 small">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('login.post') }}">@csrf
            <div class="mb-2"><label class="form-label">Username</label><input name="username" class="form-control"
                    value="{{ old('username') }}" required autofocus></div>
            <div class="mb-3"><label class="form-label">Password</label><input name="password" type="password"
                    class="form-control" required></div>
            <button class="btn btn-primary w-100" style="background:#9a55ff;border:0">Login</button>
        </form>
        <div class="d-flex justify-content-between mt-3 small">
            <a href="{{ route('register') }}">Daftar User</a>
            {{-- <a href="{{ route('adm_regist') }}">Daftar Admin</a> --}}
        </div>
    </div>
</body>

</html>
