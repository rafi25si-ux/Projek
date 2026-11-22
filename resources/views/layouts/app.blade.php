<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Studio Musik Positif')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            overflow-x: hidden;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: #181a1f;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            transition: transform 0.25s ease-in-out;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 4px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255, 193, 7, 0.12);
            color: #ffc107;
        }
        .sidebar .nav-link.active {
            background-color: #ffc107;
            color: #181a1f;
            font-weight: 600;
        }
        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .studio-card img {
            height: 220px;
            object-fit: cover;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Kiri -->
    <aside class="sidebar d-flex flex-column p-3 shadow" id="appSidebar">
        <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none text-warning fs-5 fw-bold px-2 py-2 mb-3 border-bottom border-secondary">
            <i class="bi bi-music-note-beamed me-2 fs-4"></i>
            <span>Studio Musik Positif</span>
        </a>

        <div class="small text-uppercase text-secondary fw-semibold px-2 mb-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
            Menu Utama
        </div>
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <i class="bi bi-house-door"></i> Beranda
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('katalog') || request()->routeIs('studio.detail') ? 'active' : '' }}" href="{{ route('katalog') }}">
                    <i class="bi bi-grid"></i> Katalog Studio
                </a>
            </li>

            @auth
                @if(auth()->user()->role === 'pelanggan')
                    <div class="small text-uppercase text-secondary fw-semibold px-2 mt-3 mb-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                        Area Pelanggan
                    </div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('riwayat*') ? 'active' : '' }}" href="{{ route('riwayat') }}">
                            <i class="bi bi-calendar-check"></i> Reservasi Saya
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('favorit.*') ? 'active' : '' }}" href="{{ route('favorit.index') }}">
                            <i class="bi bi-heart"></i> Studio Favorit
                        </a>
                    </li>
                @elseif(auth()->user()->role === 'admin')
                    <div class="small text-uppercase text-secondary fw-semibold px-2 mt-3 mb-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                        Administrator
                    </div>
                    <li class="nav-item">
                        <a class="nav-link bg-warning text-dark fw-semibold" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Masuk Panel Admin
                        </a>
                    </li>
                @endif
            @endauth
        </ul>

        <!-- Bagian Bawah Sidebar: Akun / Login -->
        <div class="pt-3 border-top border-secondary">
            @guest
                <div class="d-grid gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-warning btn-sm fw-semibold text-dark">
                        <i class="bi bi-person-plus me-1"></i> Daftar Akun
                    </a>
                </div>
            @else
                <div class="d-flex align-items-center justify-content-between px-2 mb-2">
                    <div class="text-truncate">
                        <div class="small fw-semibold text-white text-truncate">
                            <i class="bi bi-person-circle text-warning me-1"></i> {{ auth()->user()->name }}
                        </div>
                        <small class="text-secondary text-capitalize">{{ auth()->user()->role }}</small>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="bi bi-box-arrow-right me-1"></i> Keluar
                    </button>
                </form>
            @endguest
        </div>
    </aside>

    <!-- Konten Utama Kanan -->
    <div class="main-wrapper">
        <!-- Topbar Mobile -->
        <header class="bg-dark text-white p-3 d-flex justify-content-between align-items-center d-lg-none sticky-top shadow-sm">
            <span class="fw-bold text-warning"><i class="bi bi-music-note-beamed me-1"></i> Studio Musik Positif</span>
            <button class="btn btn-outline-warning btn-sm" type="button" onclick="document.getElementById('appSidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>
        </header>

        <main class="container-fluid px-4 px-lg-5 my-4 flex-grow-1">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="bg-white border-top text-secondary py-3 mt-auto">
            <div class="container-fluid px-4 px-lg-5 d-flex justify-content-between align-items-center small">
                <span class="fw-semibold text-dark">Studio Musik Positif</span>
                <span>&copy; {{ date('Y') }} Sistem Reservasi Studio Musik.</span>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>