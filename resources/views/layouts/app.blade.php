<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kas Arif') - Kas Pribadi</title>
    <link rel="icon" type="image/png" href="{{ asset('img/Logo2.jpeg') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @livewireStyles
    <style>
        :root {
            --purple1: #b66dff;
            --purple2: #9a55ff;
            --purple-dark: #6a1bd6
        }

        body {
            background: #f2edf3;
            font-family: 'Segoe UI', system-ui, sans-serif
        }

        .sidebar {
            background: linear-gradient(180deg, var(--purple1), var(--purple2));
            color: #fff;
            width: 260px;
            flex-shrink: 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1040;
            transition: transform .25s ease;
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .main-wrapper {
            flex: 1;
            min-width: 0;
            min-height: 100vh;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            transition: margin .25s ease;
        }

        /* Desktop collapsed: sidebar geser keluar, konten full */
        body.sidebar-collapsed .sidebar {
            transform: translateX(-105%);
        }

        body.sidebar-collapsed .main-wrapper {
            margin-left: 0;
        }

        .sidebar .nav-link {
            color: #fff;
            border-radius: 8px;
            margin: 2px 0;
            font-size: .95rem
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, .25);
            color: #fff
        }

        .sidebar .brand {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: .5px
        }

        .profile-box {
            background: rgba(255, 255, 255, .15);
            border-radius: 12px;
        }

        .topbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .card-purple {
            border: 0;
            border-radius: 14px;
            color: #fff;
            background: linear-gradient(135deg, var(--purple1), var(--purple2))
        }

        .card-green {
            background: linear-gradient(135deg, #1ed6a0, #0ea86f);
            border: 0;
            border-radius: 14px;
            color: #fff
        }

        .card-red {
            background: linear-gradient(135deg, #ff6b6b, #d63c3c);
            border: 0;
            border-radius: 14px;
            color: #fff
        }

        .card-blue {
            background: linear-gradient(135deg, #5b9bff, #2f6fed);
            border: 0;
            border-radius: 14px;
            color: #fff
        }

        .card-orange {
            background: linear-gradient(135deg, #ffb35c, #f07b1d);
            border: 0;
            border-radius: 14px;
            color: #fff
        }

        .stat-num {
            font-size: 1.4rem;
            font-weight: 800
        }

        .content-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06)
        }

        .table-responsive {
            border-radius: 12px
        }

        .btn-purple {
            background: linear-gradient(135deg, var(--purple1), var(--purple2));
            border: 0;
            color: #fff
        }

        .btn-purple:hover {
            color: #fff;
            filter: brightness(.95)
        }

        #sidebarOverlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 1039;
        }

        #sidebarOverlay.show {
            display: block;
        }

        @media(max-width:991px) {
            .sidebar {
                width: 260px;
                transform: translateX(-105%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            /* di HP, class collapsed desktop diabaikan */
            body.sidebar-collapsed .sidebar {
                transform: translateX(-105%);
            }

            body.sidebar-collapsed .sidebar.show {
                transform: translateX(0);
            }

            .stat-num {
                font-size: 1.1rem
            }
        }
    </style>
    @stack('css')
</head>

<body>
    <div class="app-wrapper">
        <div id="sidebarOverlay"></div>
        <aside class="sidebar p-3" id="sidebar">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="brand"><i class="bi bi-wallet2"></i> Kas Arif</div>
                <button class="btn btn-sm btn-light d-lg-none" id="sidebarClose" aria-label="Tutup menu"><i
                        class="bi bi-x-lg"></i></button>
            </div>
            @php $me = auth()->user(); @endphp
            <a href="{{ route('profil.index') }}" class="text-decoration-none text-white">
                <div class="profile-box d-flex align-items-center gap-2 p-2 mb-3">
                    @if ($me && $me->photo_profile)
                        <img src="{{ asset('storage/' . $me->photo_profile) }}" width="44" height="44"
                            class="rounded-circle border border-white" style="object-fit:cover" alt="Foto profil">
                    @else
                        <span class="badge text-bg-light rounded-circle p-2"><i class="bi bi-person fs-5"></i></span>
                    @endif
                    <div class="small lh-sm">
                        <div class="fw-bold">{{ $me->name ?? '' }}</div>
                        <div class="opacity-75"> • {{ ucfirst($me->role ?? '') }}</div>
                        <div class="opacity-75"><i class="bi bi-pencil-square"></i> Edit Profil</div>
                    </div>
                </div>
            </a>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                @if (auth()->check() && auth()->user()->isAdmin())
                    <a class="nav-link {{ request()->routeIs('bank.*') ? 'active' : '' }}"
                        href="{{ route('bank.index') }}"><i class="bi bi-bank"></i> Bank</a>
                    <a class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}"
                        href="{{ route('kategori.index') }}"><i class="bi bi-tags"></i> Kategori</a>
                @endif
                <a class="nav-link {{ request()->routeIs('masuk.*') ? 'active' : '' }}"
                    href="{{ route('masuk.index') }}"><i class="bi bi-arrow-down-circle"></i> Uang Masuk</a>
                <a class="nav-link {{ request()->routeIs('keluar.*') ? 'active' : '' }}"
                    href="{{ route('keluar.index') }}"><i class="bi bi-arrow-up-circle"></i> Uang Keluar</a>
                @if (auth()->check() && auth()->user()->isAdmin())
                    <a class="nav-link {{ request()->routeIs('tf.*') ? 'active' : '' }}"
                        href="{{ route('tf.index') }}"><i class="bi bi-arrow-left-right"></i> TF Antar Bank</a>
                    <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}"
                        href="{{ route('laporan.index') }}"><i class="bi bi-file-earmark-text"></i> Laporan</a>
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                        href="{{ route('users.index') }}"><i class="bi bi-people"></i> User</a>
                @endif
                <a class="nav-link {{ request()->routeIs('profil.*') ? 'active' : '' }}"
                    href="{{ route('profil.index') }}"><i class="bi bi-person-circle"></i> Profil Saya</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">@csrf
                    <button class="btn btn-light btn-sm w-100"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </nav>
        </aside>
        <div class="main-wrapper">
            <div class="topbar px-3 py-2 d-flex justify-content-between align-items-center sticky-top gap-2">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-secondary btn-sm d-lg-none" id="sidebarToggle"
                        aria-label="Buka menu">
                        <i class="bi bi-list"></i>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm d-none d-lg-inline-block" id="sidebarToggleDesktop"
                        aria-label="Buka/tutup menu">
                        <i class="bi bi-list"></i>
                    </button>
                    <h5 class="m-0">@yield('title', 'Dashboard')</h5>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-bg-light d-none d-sm-inline">{{ now()->format('d M Y') }}</span>
                    <a href="{{ route('profil.index') }}" class="text-decoration-none">
                        @if ($me && $me->photo_profile)
                            <img src="{{ asset('storage/' . $me->photo_profile) }}" width="36" height="36"
                                class="rounded-circle border" style="object-fit:cover" alt="Foto profil">
                        @else
                            <span class="badge text-bg-secondary rounded-circle p-2"><i class="bi bi-person"></i></span>
                        @endif
                    </a>
                </div>
            </div>
            <div class="p-3">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="m-0">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script>
        // auto format rupiah: input.rupiah-input
        document.addEventListener('input', function(e) {
            if (e.target.classList && e.target.classList.contains('rupiah-input')) {
                let v = e.target.value.replace(/[^0-9]/g, '');
                if (!v) {
                    e.target.value = '';
                    return;
                }
                e.target.value = Number(v).toLocaleString('id-ID');
            }
        });

        // sidebar toggle HP + toggle desktop (fixed)
        (function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const open = () => {
                sidebar && sidebar.classList.add('show');
                overlay && overlay.classList.add('show');
            };
            const close = () => {
                sidebar && sidebar.classList.remove('show');
                overlay && overlay.classList.remove('show');
            };
            document.getElementById('sidebarToggle')?.addEventListener('click', open);
            document.getElementById('sidebarClose')?.addEventListener('click', close);
            overlay?.addEventListener('click', close);
            sidebar?.querySelectorAll('.nav-link').forEach(a => a.addEventListener('click', () => {
                if (window.innerWidth < 992) close();
            }));

            // Desktop: buka/tutup sidebar fixed, ingat pilihan user
            try {
                if (window.innerWidth >= 992 && localStorage.getItem('sidebar-collapsed') === '1') {
                    document.body.classList.add('sidebar-collapsed');
                }
            } catch (e) {}
            document.getElementById('sidebarToggleDesktop')?.addEventListener('click', () => {
                document.body.classList.toggle('sidebar-collapsed');
                try {
                    localStorage.setItem('sidebar-collapsed', document.body.classList.contains(
                        'sidebar-collapsed') ? '1' : '0');
                } catch (e) {}
            });
        })();

        // DataTables untuk tabel biasa (bukan Livewire)
        function initStaticTables() {
            if (!window.jQuery || !jQuery.fn.DataTable) return;
            jQuery('table.datatable:not(.livewire-table)').each(function() {
                if (jQuery.fn.DataTable.isDataTable(this)) return;
                jQuery(this).DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
                    }
                });
            });
        }
        document.addEventListener('DOMContentLoaded', initStaticTables);
    </script>
    @livewireScripts
    @stack('js')
</body>

</html>
