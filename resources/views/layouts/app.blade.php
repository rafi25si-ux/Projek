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
            background: linear-gradient(180deg, #1b1d23 0%, #14161b 100%);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Efek Logo Brand */
        .sidebar-brand {
            border-radius: 12px;
            transition: all 0.25s ease;
        }
        .sidebar-brand i {
            display: inline-block;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), text-shadow 0.3s ease;
        }
        .sidebar-brand:hover i {
            transform: rotate(-12deg) scale(1.15);
            text-shadow: 0 0 14px rgba(255, 193, 7, 0.65);
        }

        /* Efek Item Menu Sidebar */
        .sidebar .nav-link {
            position: relative;
            overflow: hidden;
            color: #adb5bd;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 6px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 11px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar .nav-link i {
            font-size: 1.05rem;
            transition: transform 0.25s ease, color 0.25s ease;
        }
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 22%;
            height: 56%;
            width: 3.5px;
            border-radius: 0 4px 4px 0;
            background-color: #ffc107;
            transform: scaleY(0);
            transition: transform 0.25s ease;
        }

        /* Hover state menu tidak aktif */
        .sidebar .nav-link:not(.active):hover {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffd54f;
            transform: translateX(5px);
        }
        .sidebar .nav-link:not(.active):hover::before {
            transform: scaleY(1);
        }
        .sidebar .nav-link:not(.active):hover i {
            transform: scale(1.15);
            color: #ffc107;
        }

        /* Active state menu terpilih */
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%);
            color: #181a1f;
            font-weight: 700;
            box-shadow: 0 6px 18px rgba(255, 193, 7, 0.28);
            transform: translateX(2px);
        }
        .sidebar .nav-link.active i {
            color: #181a1f;
            transform: scale(1.08);
        }

        /* Kartu Profil Bawah & Tombol Keluar */
        .sidebar-profile-card {
            background-color: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 12px;
            padding: 10px 12px;
            transition: background-color 0.25s ease, border-color 0.25s ease;
        }
        .sidebar-profile-card:hover {
            background-color: rgba(255, 255, 255, 0.07);
            border-color: rgba(255, 193, 7, 0.25);
        }
        .btn-logout-modern {
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.25s ease;
        }
        .btn-logout-modern:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
            box-shadow: 0 4px 14px rgba(220, 53, 69, 0.35);
            transform: translateY(-1px);
        }

        /* Background Kalem Bernuansa Studio Musik */
        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background:
                radial-gradient(circle at 85% 15%, rgba(255, 193, 7, 0.09) 0%, transparent 45%),
                radial-gradient(circle at 15% 85%, rgba(24, 26, 31, 0.06) 0%, transparent 45%),
                linear-gradient(180deg, rgba(244, 246, 249, 0.88) 0%, rgba(244, 246, 249, 0.93) 100%),
                url('https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?auto=format&fit=crop&w=1600&q=80') center/cover fixed no-repeat;
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
        <a href="{{ route('home') }}" class="sidebar-brand d-flex align-items-center text-decoration-none text-warning fs-5 fw-bold px-2 py-2 mb-3 border-bottom border-secondary border-opacity-50">
            <i class="bi bi-music-note-beamed me-2 fs-4"></i>
            <span>Studio Musik Positif</span>
        </a>

        <div class="small text-uppercase text-secondary fw-semibold px-2 mb-2" style="font-size: 0.72rem; letter-spacing: 0.8px;">
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
                    <div class="small text-uppercase text-secondary fw-semibold px-2 mt-3 mb-2" style="font-size: 0.72rem; letter-spacing: 0.8px;">
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
                    <div class="small text-uppercase text-secondary fw-semibold px-2 mt-3 mb-2" style="font-size: 0.72rem; letter-spacing: 0.8px;">
                        Administrator
                    </div>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Masuk Panel Admin
                        </a>
                    </li>
                @endif
            @endauth
        </ul>

        <!-- Bagian Bawah Sidebar: Akun / Login (Disembunyikan saat di halaman Login & Register) -->
        @if(!request()->routeIs('login', 'register', 'password.request'))
            <div class="pt-3 border-top border-secondary border-opacity-50">
                @guest
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm rounded-3">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-warning btn-sm fw-semibold text-dark rounded-3">
                            <i class="bi bi-person-plus me-1"></i> Daftar Akun
                        </a>
                    </div>
                @else
                    <div class="sidebar-profile-card d-flex align-items-center justify-content-between mb-2">
                        <div class="text-truncate">
                            <div class="small fw-semibold text-white text-truncate">
                                <i class="bi bi-person-circle text-warning me-1"></i> {{ auth()->user()->name }}
                            </div>
                            <small class="text-secondary text-capitalize" style="font-size: 0.75rem;">{{ auth()->user()->role }}</small>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 btn-logout-modern">
                            <i class="bi bi-box-arrow-right me-1"></i> Keluar
                        </button>
                    </form>
                @endguest
            </div>
        @endif
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

        <footer class="bg-white bg-opacity-75 border-top text-secondary py-3 mt-auto">
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