<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin - Studio Musik Positif')</title>
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
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Efek Logo Brand Admin */
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
            background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%) !important;
            color: #181a1f !important;
            font-weight: 700;
            box-shadow: 0 6px 18px rgba(255, 193, 7, 0.28);
            transform: translateX(2px);
        }
        .sidebar .nav-link.active i {
            color: #181a1f !important;
            transform: scale(1.08);
        }

        /* Tombol Bawah Sidebar */
        .btn-website-modern {
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.25s ease;
        }
        .btn-website-modern:hover {
            background-color: rgba(255, 255, 255, 0.12);
            border-color: #ffc107;
            color: #ffc107;
            transform: translateY(-1px);
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

        /* Background Kalem Panel Admin */
        .admin-main-wrapper {
            background:
                radial-gradient(circle at 85% 15%, rgba(255, 193, 7, 0.08) 0%, transparent 45%),
                radial-gradient(circle at 15% 85%, rgba(24, 26, 31, 0.05) 0%, transparent 45%),
                linear-gradient(180deg, rgba(244, 246, 249, 0.90) 0%, rgba(244, 246, 249, 0.94) 100%),
                url('https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?auto=format&fit=crop&w=1600&q=80') center/cover fixed no-repeat;
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="d-flex">
    <aside class="sidebar d-flex flex-column flex-shrink-0 text-white p-3 shadow">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand d-flex align-items-center text-decoration-none text-warning fw-bold fs-5 px-2 py-2 mb-3 border-bottom border-secondary border-opacity-50">
            <i class="bi bi-music-note-beamed me-2 fs-4"></i>
            <span>Admin Studio</span>
        </a>

        <div class="small text-uppercase text-secondary fw-semibold px-2 mb-2" style="font-size: 0.72rem; letter-spacing: 0.8px;">
            Manajemen Sistem
        </div>
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.studio.index') }}" class="nav-link {{ request()->routeIs('admin.studio.*') ? 'active' : '' }}">
                    <i class="bi bi-speaker"></i> Kelola Studio
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.fasilitas.index') }}" class="nav-link {{ request()->routeIs('admin.fasilitas.*') ? 'active' : '' }}">
                    <i class="bi bi-tools"></i> Fasilitas
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.promo.index') }}" class="nav-link {{ request()->routeIs('admin.promo.*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated"></i> Kode Promo
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.reservasi.index') }}" class="nav-link {{ request()->routeIs('admin.reservasi.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Data Reservasi
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.pembayaran.index') }}" class="nav-link {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i> Verifikasi Pembayaran
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Data Pelanggan
                </a>
            </li>
        </ul>

        <div class="pt-3 border-top border-secondary border-opacity-50">
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm w-100 mb-2 btn-website-modern">
                <i class="bi bi-globe me-1"></i> Lihat Website
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 btn-logout-modern">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-grow-1 d-flex flex-column admin-main-wrapper">
        <header class="bg-white bg-opacity-75 shadow-sm px-4 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">@yield('page_title', 'Dashboard Admin')</h5>
            <span class="text-muted small"><i class="bi bi-person-badge text-warning me-1"></i> {{ auth()->user()->name }}</span>
        </header>

        <main class="p-4 flex-grow-1">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>