@php
    $mode = $mode ?? 'login';
    $isAdmin = $isAdmin ?? false;
    $pageTitle = $mode === 'register'
        ? (($isAdmin ? 'Daftar Admin' : 'Daftar User') . ' - Kas Arif')
        : 'Masuk - Kas Arif';
    $pageHeading = $mode === 'register'
        ? ($isAdmin ? 'Buat akun admin.' : 'Buat akun.')
        : 'Selamat datang kembali.';
    $pageSubheading = $mode === 'register'
        ? 'Isi data berikut. Akun langsung bisa dipakai setelah disimpan.'
        : 'Masuk untuk melanjutkan mencatat kas.';
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2e1065">
    <title>{{ $pageTitle }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@1&amp;family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --auth-deep: #2e1065;
            --auth-mid: #5b21b6;
            --auth-bright: #7c3aed;
            --auth-lav: #c4b5fd;
            --auth-paper: #fbfaff;
            --auth-ink: #1d1528;
            --auth-muted: #625b70;
            --auth-line: #ece6f6;
            --auth-border: #9488ad;
            --auth-error: #991b1b;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            -webkit-text-size-adjust: 100%;
        }

        [hidden] {
            display: none !important;
        }

        .auth-body {
            margin: 0;
            background: var(--auth-paper);
            color: var(--auth-ink);
            font-family: 'Plus Jakarta Sans', 'Segoe UI', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .auth-page {
            position: relative;
            isolation: isolate;
            min-height: 100vh;
            min-height: 100dvh;
            overflow: hidden;
        }

        .auth-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            background:
                radial-gradient(95% 120% at 0% 42%, rgba(30, 7, 64, .62) 0%, rgba(30, 7, 64, 0) 72%),
                linear-gradient(110deg,
                    #2e1065 0%,
                    #3b0764 10%,
                    #4c1d95 24%,
                    #5b21b6 37%,
                    #6d28d9 48%,
                    #8b5cf6 58%,
                    #c4b5fd 70%,
                    #ede9fe 80%,
                    #fbfaff 90%,
                    #ffffff 100%);
        }

        .auth-grain {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: .4;
            mix-blend-mode: soft-light;
            pointer-events: none;
        }

        .auth-shell {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(360px, .92fr);
            align-items: center;
            gap: clamp(40px, 6vw, 88px);
            width: min(calc(100% - 80px), 1200px);
            min-height: 100vh;
            min-height: 100dvh;
            margin: 0 auto;
            padding: 56px 0;
        }

        .auth-brand {
            display: flex;
            flex-direction: column;
            gap: 36px;
            max-width: 560px;
            color: #fff;
        }

        .brand-top {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .brand-avatar {
            width: 48px;
            height: 48px;
            flex: 0 0 auto;
            border: 1.5px solid rgba(255, 255, 255, .76);
            border-radius: 50%;
            object-fit: cover;
        }

        .brand-name {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -.01em;
        }

        .brand-sub {
            margin-top: 2px;
            color: #e9d5ff;
            font-size: 12px;
            line-height: 1.35;
        }

        .brand-main {
            max-width: 560px;
        }

        .brand-headline {
            max-width: 12.5ch;
            margin: 0 0 18px;
            color: #fff;
            font-size: clamp(36px, 4.4vw, 58px);
            font-weight: 800;
            line-height: 1.04;
            letter-spacing: -.035em;
            text-wrap: balance;
        }

        .brand-headline em {
            font-family: 'Instrument Serif', Georgia, serif;
            font-style: italic;
            font-weight: 400;
            letter-spacing: -.01em;
        }

        .brand-copy {
            max-width: 40ch;
            margin: 0;
            color: #f3e8ff;
            font-size: 16px;
            line-height: 1.65;
        }

        .brand-foot {
            display: flex;
            align-items: center;
            gap: 8px;
            max-width: 420px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, .22);
            color: #e9d5ff;
            font-size: 12px;
            line-height: 1.4;
        }

        .brand-foot i {
            font-size: 14px;
        }

        .auth-form-panel {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .auth-card {
            --mx: -240px;
            --my: -240px;
            position: relative;
            width: 100%;
            max-width: 440px;
            padding: 1px;
            border-radius: 24px;
            background: radial-gradient(260px circle at var(--mx) var(--my),
                    #8b5cf6 0%,
                    #d8ccef 48%,
                    #e9e2f7 100%);
            box-shadow:
                0 40px 90px -48px rgba(46, 16, 101, .55),
                0 18px 40px -32px rgba(46, 16, 101, .28);
            animation: auth-card-in .65s cubic-bezier(.22, 1, .36, 1) both;
        }

        .auth-card-inner {
            padding: 36px 36px 26px;
            border-radius: 23px;
            background: #fff;
        }

        .auth-card h1 {
            margin: 0;
            color: var(--auth-ink);
            font-size: 28px;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -.03em;
        }

        .auth-card-sub {
            margin: 8px 0 0;
            color: var(--auth-muted);
            font-size: 14px;
            line-height: 1.55;
        }

        .auth-form {
            margin-top: 26px;
        }

        .auth-alert + .auth-form {
            margin-top: 18px;
        }

        .auth-field {
            position: relative;
        }

        .auth-field > .bi {
            position: absolute;
            top: 50%;
            left: 16px;
            z-index: 1;
            color: #8b809f;
            font-size: 17px;
            line-height: 1;
            pointer-events: none;
            transform: translateY(-50%);
            transition: color .18s ease;
        }

        .auth-field:focus-within > .bi {
            color: var(--auth-bright);
        }

        .auth-form .form-label {
            display: block;
            margin: 0 0 7px;
            color: #3f3a4d;
            font-size: 12.5px;
            font-weight: 600;
            line-height: 1.4;
        }

        .auth-form .form-control {
            width: 100%;
            height: 52px;
            padding: 13px 16px 13px 44px;
            border: 1px solid var(--auth-border);
            border-radius: 12px;
            background: #fff;
            color: var(--auth-ink);
            font-family: inherit;
            font-size: 16px;
            line-height: 24px;
            box-shadow: none;
            transition: border-color .18s ease, outline-color .18s ease;
        }

        .auth-form .form-control:hover {
            border-color: #7f7398;
        }

        .auth-form .form-control:focus {
            border-color: var(--auth-mid);
            outline: 2px solid var(--auth-mid);
            outline-offset: 2px;
            box-shadow: none;
        }

        .auth-form .form-control.is-invalid {
            border-color: #b91c1c;
        }

        .auth-form .form-control.is-invalid:focus {
            border-color: #b91c1c;
            outline-color: #b91c1c;
        }

        .auth-field-group {
            margin-bottom: 16px;
        }

        .auth-password .form-control {
            padding-right: 54px;
        }

        .auth-eye {
            position: absolute;
            top: 50%;
            right: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            padding: 0;
            border: 0;
            border-radius: 9px;
            background: transparent;
            color: #6f6680;
            cursor: pointer;
            transform: translateY(-50%);
        }

        .auth-eye:hover {
            background: #f4efff;
            color: var(--auth-mid);
        }

        .auth-eye:focus-visible {
            outline: 2px solid var(--auth-mid);
            outline-offset: 1px;
        }

        .auth-form .form-check {
            display: flex;
            align-items: center;
            gap: 9px;
            min-height: 44px;
            margin: 2px 0 18px;
            padding-left: 0;
        }

        .auth-form .form-check-input {
            flex: 0 0 auto;
            width: 18px;
            height: 18px;
            margin: 0;
            border-color: var(--auth-border);
        }

        .auth-form .form-check-input:checked {
            border-color: var(--auth-mid);
            background-color: var(--auth-mid);
        }

        .auth-form .form-check-input:focus {
            border-color: var(--auth-mid);
            outline: 2px solid var(--auth-mid);
            outline-offset: 2px;
            box-shadow: none;
        }

        .auth-form .form-check-label {
            color: #3f3a4d;
            font-size: 13.5px;
            cursor: pointer;
        }

        .auth-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            min-height: 52px;
            padding: 12px 18px;
            overflow: hidden;
            isolation: isolate;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
            color: #fff;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: -.005em;
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .auth-btn::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 1;
            background-image: linear-gradient(-45deg, transparent 58%, rgba(255, 255, 255, .18) 68%, transparent 100%);
            background-repeat: no-repeat;
            background-size: 250% 250%;
            background-position: -100% -100%;
            pointer-events: none;
            transition: background-position .65s ease-in-out;
        }

        .auth-btn:hover::before,
        .auth-btn:focus-visible::before {
            background-position: 100% 100%;
        }

        .auth-btn > * {
            position: relative;
            z-index: 2;
        }

        .auth-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px -14px rgba(91, 33, 182, .65);
        }

        .auth-btn:active {
            transform: translateY(0);
        }

        .auth-btn:focus-visible {
            outline: 3px solid var(--auth-deep);
            outline-offset: 3px;
        }

        .auth-btn:disabled {
            opacity: .82;
            cursor: wait;
            transform: none;
            box-shadow: none;
        }

        .auth-btn-spinner {
            margin-left: 0;
        }

        .auth-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 20px 0 0;
            padding: 12px 14px;
            border: 1px solid;
            border-radius: 12px;
            font-size: 13.5px;
            line-height: 1.5;
        }

        .auth-alert > i {
            flex: 0 0 auto;
            margin-top: 1px;
        }

        .auth-alert ul {
            margin: 0;
            padding-left: 1.1rem;
        }

        .auth-alert-error {
            border-color: #fecaca;
            background: #fef2f2;
            color: var(--auth-error);
        }

        .auth-alert-success {
            border-color: #bbf7d0;
            background: #f0fdf4;
            color: #166534;
        }

        .auth-card-foot {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid var(--auth-line);
            color: var(--auth-muted);
            font-size: 13px;
            line-height: 1.5;
            text-align: center;
        }

        .auth-card-foot a {
            color: var(--auth-mid);
            font-weight: 600;
            text-underline-offset: 2px;
        }

        .auth-card-foot a:focus-visible {
            border-radius: 4px;
            outline: 2px solid var(--auth-deep);
            outline-offset: 2px;
        }

        .auth-photo-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 10px;
        }

        .auth-photo-preview {
            width: 64px;
            height: 64px;
            border: 1px solid var(--auth-border);
            border-radius: 50%;
            object-fit: cover;
        }

        .auth-photo-clear {
            padding: 0;
            border: 0;
            background: transparent;
            color: var(--auth-mid);
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            text-decoration: underline;
            text-underline-offset: 2px;
            cursor: pointer;
        }

        .auth-photo-clear:focus-visible {
            border-radius: 4px;
            outline: 2px solid var(--auth-deep);
            outline-offset: 2px;
        }

        .auth-help {
            margin-top: 6px;
            color: var(--auth-muted);
            font-size: 12px;
            line-height: 1.4;
        }

        .auth-form .form-control[type="file"] {
            height: auto;
            padding: 10px 12px;
            font-size: 14px;
            line-height: 24px;
        }

        .auth-form .form-control[type="file"]::file-selector-button {
            margin-right: 10px;
            padding: 6px 10px;
            border: 0;
            border-radius: 6px;
            background: #f4efff;
            color: var(--auth-deep);
            font-family: inherit;
            font-weight: 600;
        }

        @keyframes auth-card-in {
            from {
                opacity: 0;
                transform: translateY(14px) scale(.995);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        @media (min-width: 901px) and (max-width: 1080px) {
            .auth-shell {
                grid-template-columns: minmax(0, 1fr) minmax(340px, .9fr);
                gap: 40px;
                width: min(calc(100% - 56px), 1040px);
            }

            .brand-headline {
                font-size: clamp(34px, 4.2vw, 48px);
            }

            .auth-card {
                max-width: 400px;
            }

            .auth-card-inner {
                padding: 32px 30px 24px;
            }
        }

        @media (max-width: 900px) {
            .auth-bg {
                background:
                    radial-gradient(110% 80% at 8% 0%, rgba(30, 7, 64, .55) 0%, rgba(30, 7, 64, 0) 70%),
                    linear-gradient(180deg,
                        #2e1065 0%,
                        #3b0764 12%,
                        #4c1d95 26%,
                        #5b21b6 40%,
                        #7c3aed 52%,
                        #b69cff 64%,
                        #e9e1ff 76%,
                        #fbfaff 88%,
                        #ffffff 100%);
            }

            .auth-shell {
                display: block;
                width: 100%;
                min-height: 0;
                padding: 0;
            }

            .auth-brand {
                max-width: none;
                padding: 28px 20px 52px;
                gap: 20px;
            }

            .brand-avatar {
                width: 42px;
                height: 42px;
            }

            .brand-name {
                font-size: 16px;
            }

            .brand-sub {
                font-size: 11.5px;
            }

            .brand-headline {
                max-width: 16ch;
                margin-bottom: 0;
                font-size: clamp(30px, 7vw, 38px);
                line-height: 1.06;
            }

            .brand-copy,
            .brand-foot {
                display: none;
            }

            .auth-form-panel {
                display: block;
                padding: 0 16px 88px;
                margin-top: -34px;
            }

            .auth-card {
                max-width: 520px;
                margin: 0 auto;
                border-radius: 22px;
            }

            .auth-card-inner {
                padding: 28px 22px 24px;
                border-radius: 21px;
            }

            .auth-card h1 {
                font-size: 24px;
            }

            .auth-card-sub {
                font-size: 13.5px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .auth-card {
                animation: none;
            }

            .auth-btn,
            .auth-btn::before {
                transition: none;
            }

            .auth-btn:hover {
                transform: none;
            }
        }
    </style>
</head>

<body class="auth-body">
    <main class="auth-page">
        <div class="auth-bg" aria-hidden="true">
            <svg class="auth-grain" xmlns="http://www.w3.org/2000/svg">
                <filter id="authNoise">
                    <feTurbulence type="fractalNoise" baseFrequency="0.9" numOctaves="3" stitchTiles="stitch" />
                    <feColorMatrix type="saturate" values="0" />
                </filter>
                <rect width="100%" height="100%" filter="url(#authNoise)" opacity="0.075" />
            </svg>
        </div>

        <div class="auth-shell">
            <section class="auth-brand" aria-label="Tentang Kas Arif">
                <div class="brand-top">
                    <img class="brand-avatar" src="{{ asset('img/NovoResume.jpeg') }}" width="48" height="48"
                        alt="Foto profil Kas Arif">
                    <div>
                        <div class="brand-name">Kas Arif</div>
                        <div class="brand-sub">Keuangan pribadi</div>
                    </div>
                </div>

                <div class="brand-main">
                    <p class="brand-headline">Uang masuk, uang keluar, sisanya <em>jelas.</em></p>
                    <p class="brand-copy">Satu tempat untuk mencatat pemasukan, pengeluaran, dan saldo bank. Laporan
                        bulanan bisa diunduh kapan saja.</p>
                </div>

                <div class="brand-foot">
                    <i class="bi bi-shield-lock" aria-hidden="true"></i>
                    <span>Akses terbatas untuk pengguna terdaftar.</span>
                </div>
            </section>

            <section class="auth-form-panel">
                <div class="auth-card" data-magic-card>
                    <div class="auth-card-inner">
                        <h1>{{ $pageHeading }}</h1>
                        <p class="auth-card-sub">{{ $pageSubheading }}</p>

                        @if ($mode === 'login' && session('success'))
                            <div class="auth-alert auth-alert-success" role="status">
                                <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="auth-alert auth-alert-error" id="auth-errors" role="alert">
                                <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                                <div>
                                    <ul>
                                        @foreach ($errors->all() as $e)
                                            <li>{{ $e }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if ($mode === 'login')
                            <form method="POST" action="{{ route('login.post') }}" class="auth-form" data-auth-form>
                                @csrf

                                <div class="auth-field-group">
                                    <label class="form-label" for="username">Username</label>
                                    <div class="auth-field">
                                        <i class="bi bi-person" aria-hidden="true"></i>
                                        <input id="username" name="username" type="text"
                                            class="form-control @error('username') is-invalid @enderror"
                                            value="{{ old('username') }}" required autocomplete="username"
                                            autocapitalize="none" spellcheck="false" data-autofocus
                                            @if ($errors->has('username')) aria-describedby="auth-errors" aria-invalid="true" @endif>
                                    </div>
                                </div>

                                <div class="auth-field-group">
                                    <label class="form-label" for="password">Password</label>
                                    <div class="auth-field auth-password">
                                        <i class="bi bi-lock" aria-hidden="true"></i>
                                        <input id="password" name="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" required
                                            autocomplete="current-password"
                                            @if ($errors->has('password')) aria-describedby="auth-errors" aria-invalid="true" @endif>
                                        <button type="button" class="auth-eye" data-password-toggle="password"
                                            aria-label="Tampilkan password" aria-pressed="false">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        value="1" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">Ingat saya</label>
                                </div>

                                <button class="auth-btn" type="submit" data-submit data-label="Masuk"
                                    data-loading-text="Memeriksa...">
                                    <span class="auth-btn-text">Masuk</span>
                                    <span class="spinner-border spinner-border-sm auth-btn-spinner" aria-hidden="true"
                                        hidden></span>
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ $isAdmin ? url('/adm_regist') : url('/register') }}"
                                enctype="multipart/form-data" class="auth-form" data-auth-form>
                                @csrf

                                <div class="auth-field-group">
                                    <label class="form-label" for="name">Nama</label>
                                    <div class="auth-field">
                                        <i class="bi bi-person" aria-hidden="true"></i>
                                        <input id="name" name="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" required autocomplete="name" data-autofocus
                                            @if ($errors->has('name')) aria-describedby="auth-errors" aria-invalid="true" @endif>
                                    </div>
                                </div>

                                <div class="auth-field-group">
                                    <label class="form-label" for="username">Username</label>
                                    <div class="auth-field">
                                        <i class="bi bi-at" aria-hidden="true"></i>
                                        <input id="username" name="username" type="text"
                                            class="form-control @error('username') is-invalid @enderror"
                                            value="{{ old('username') }}" required autocomplete="username"
                                            autocapitalize="none" spellcheck="false"
                                            @if ($errors->has('username')) aria-describedby="auth-errors" aria-invalid="true" @endif>
                                    </div>
                                </div>

                                <div class="auth-field-group">
                                    <label class="form-label" for="password">Password</label>
                                    <div class="auth-field auth-password">
                                        <i class="bi bi-lock" aria-hidden="true"></i>
                                        <input id="password" name="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" required
                                            autocomplete="new-password"
                                            @if ($errors->has('password')) aria-describedby="auth-errors" aria-invalid="true" @endif>
                                        <button type="button" class="auth-eye" data-password-toggle="password"
                                            aria-label="Tampilkan password" aria-pressed="false">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="auth-help">Minimal 5 karakter.</div>
                                </div>

                                <div class="auth-field-group">
                                    <label class="form-label" for="password_confirmation">Konfirmasi password</label>
                                    <div class="auth-field auth-password">
                                        <i class="bi bi-lock" aria-hidden="true"></i>
                                        <input id="password_confirmation" name="password_confirmation" type="password"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            required autocomplete="new-password"
                                            @if ($errors->has('password_confirmation')) aria-describedby="auth-errors" aria-invalid="true" @endif>
                                        <button type="button" class="auth-eye"
                                            data-password-toggle="password_confirmation"
                                            aria-label="Tampilkan password" aria-pressed="false">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="auth-field-group">
                                    <label class="form-label" for="photo_profile">Foto profil (opsional)</label>
                                    <input id="photo_profile" name="photo_profile" type="file"
                                        class="form-control @error('photo_profile') is-invalid @enderror" accept="image/*"
                                        @if ($errors->has('photo_profile')) aria-describedby="auth-errors" aria-invalid="true" @endif>
                                    <div class="auth-help">Format gambar, maksimal 2 MB.</div>
                                    <div class="auth-photo-row" id="photoPreviewRow" hidden>
                                        <img class="auth-photo-preview" id="photoPreview" alt="Pratinjau foto profil">
                                        <button type="button" class="auth-photo-clear" id="photoClear">Hapus
                                            pilihan</button>
                                    </div>
                                </div>

                                <button class="auth-btn" type="submit" data-submit
                                    data-label="{{ $isAdmin ? 'Buat akun admin' : 'Buat akun' }}"
                                    data-loading-text="Menyimpan...">
                                    <span
                                        class="auth-btn-text">{{ $isAdmin ? 'Buat akun admin' : 'Buat akun' }}</span>
                                    <span class="spinner-border spinner-border-sm auth-btn-spinner" aria-hidden="true"
                                        hidden></span>
                                </button>
                            </form>
                        @endif

                        @if ($mode === 'login')
                            <div class="auth-card-foot">Butuh akun? Hubungi admin lewat menu User.</div>
                        @else
                            <div class="auth-card-foot">Sudah punya akun? <a
                                    href="{{ route('login') }}">Masuk</a></div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script>
        (function () {
            // Tampilkan / sembunyikan password
            document.querySelectorAll('[data-password-toggle]').forEach(function (btn) {
                var input = document.getElementById(btn.dataset.passwordToggle);
                var icon = btn.querySelector('i');
                if (!input) return;

                btn.addEventListener('click', function () {
                    var show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    btn.setAttribute('aria-pressed', String(show));
                    btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
                    if (icon) {
                        icon.classList.toggle('bi-eye', !show);
                        icon.classList.toggle('bi-eye-slash', show);
                    }
                    input.focus();
                });
            });

            // Spotlight border mengikuti pointer (adaptasi Magic UI magic-card)
            document.querySelectorAll('[data-magic-card]').forEach(function (card) {
                var reset = function () {
                    card.style.setProperty('--mx', '-240px');
                    card.style.setProperty('--my', '-240px');
                };
                reset();
                card.addEventListener('pointermove', function (event) {
                    var rect = card.getBoundingClientRect();
                    card.style.setProperty('--mx', (event.clientX - rect.left) + 'px');
                    card.style.setProperty('--my', (event.clientY - rect.top) + 'px');
                });
                card.addEventListener('pointerleave', reset);
                window.addEventListener('blur', reset);
            });

            // Fokus otomatis hanya di layar besar, agar keyboard HP tidak langsung muncul
            if (window.matchMedia('(min-width: 992px)').matches) {
                var autofocus = document.querySelector('[data-autofocus]');
                if (autofocus) {
                    autofocus.focus({ preventScroll: true });
                }
            }

            // Cegah klik ganda saat form dikirim
            document.querySelectorAll('form[data-auth-form]').forEach(function (form) {
                form.addEventListener('submit', function () {
                    var btn = form.querySelector('[data-submit]');
                    if (!btn || btn.disabled) return;

                    form.setAttribute('aria-busy', 'true');
                    btn.disabled = true;
                    var label = btn.querySelector('.auth-btn-text');
                    if (label) label.textContent = btn.dataset.loadingText || 'Memproses...';
                    var spinner = btn.querySelector('.auth-btn-spinner');
                    if (spinner) spinner.hidden = false;
                });
            });

            // Kembalikan tombol saat halaman dipulihkan dari cache navigasi
            window.addEventListener('pageshow', function () {
                document.querySelectorAll('form[data-auth-form]').forEach(function (form) {
                    form.removeAttribute('aria-busy');
                    var btn = form.querySelector('[data-submit]');
                    if (!btn) return;
                    btn.disabled = false;
                    var label = btn.querySelector('.auth-btn-text');
                    if (label && btn.dataset.label) label.textContent = btn.dataset.label;
                    var spinner = btn.querySelector('.auth-btn-spinner');
                    if (spinner) spinner.hidden = true;
                });
            });

            // Pratinjau foto profil di halaman register
            var photoInput = document.getElementById('photo_profile');
            var photoPreview = document.getElementById('photoPreview');
            var photoRow = document.getElementById('photoPreviewRow');
            var photoClear = document.getElementById('photoClear');
            var photoUrl = null;

            if (photoInput && photoPreview && photoRow) {
                photoInput.addEventListener('change', function () {
                    var file = photoInput.files && photoInput.files[0];
                    if (photoUrl) {
                        URL.revokeObjectURL(photoUrl);
                        photoUrl = null;
                    }
                    if (!file || !file.type.startsWith('image/')) {
                        photoPreview.removeAttribute('src');
                        photoRow.hidden = true;
                        return;
                    }
                    photoUrl = URL.createObjectURL(file);
                    photoPreview.src = photoUrl;
                    photoRow.hidden = false;
                });

                if (photoClear) {
                    photoClear.addEventListener('click', function () {
                        photoInput.value = '';
                        if (photoUrl) {
                            URL.revokeObjectURL(photoUrl);
                            photoUrl = null;
                        }
                        photoPreview.removeAttribute('src');
                        photoRow.hidden = true;
                        photoInput.focus();
                    });
                }
            }
        })();
    </script>
</body>

</html>
