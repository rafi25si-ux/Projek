<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin - Studio Musik Positif')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; width: 250px; background: #212529; }
        .sidebar .nav-link { color: #adb5bd; padding: 0.75rem 1.25rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255, 193, 7, 0.15); border-left: 3px solid #ffc107; }
    </style>
    @stack('styles')
</head>
<body>
<div class="d-flex">
    <aside class="sidebar d-flex flex-column flex-shrink-0 text-white">
        <div class="p-3 border-bottom border-secondary">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-warning fw-bold fs-5">
                <i class="bi bi-music-note-beamed me-1"></i> Admin Studio
            </a>
        </div>
        <ul class="nav nav-pills flex-column mb-auto py-2">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.studio.index') }}" class="nav-link {{ request()->routeIs('admin.studio.*') ? 'active' : '' }}">
                    <i class="bi bi-speaker me-2"></i> Kelola Studio
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.fasilitas.index') }}" class="nav-link {{ request()->routeIs('admin.fasilitas.*') ? 'active' : '' }}">
                    <i class="bi bi-tools me-2"></i> Fasilitas
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.promo.index') }}" class="nav-link {{ request()->routeIs('admin.promo.*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated me-2"></i> Kode Promo
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.reservasi.index') }}" class="nav-link {{ request()->routeIs('admin.reservasi.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check me-2"></i> Data Reservasi
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.pembayaran.index') }}" class="nav-link {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card me-2"></i> Verifikasi Pembayaran
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Data Pelanggan
                </a>
            </li>
        </ul>
        <div class="p-3 border-top border-secondary">
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm w-100 mb-2">
                <i class="bi bi-globe me-1"></i> Lihat Website
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-grow-1 d-flex flex-column">
        <header class="bg-white shadow-sm px-4 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">@yield('page_title', 'Dashboard Admin')</h5>
            <span class="text-muted small"><i class="bi bi-person-badge me-1"></i> {{ auth()->user()->name }}</span>
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