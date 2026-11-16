<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Studio Musik Positif')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: 700; letter-spacing: 0.5px; }
        .studio-card img { height: 210px; object-fit: cover; }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand text-warning" href="{{ route('home') }}">
                <i class="bi bi-music-note-beamed me-1"></i> Studio Musik Positif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('katalog') ? 'active' : '' }}" href="{{ route('katalog') }}">Katalog Studio</a>
                    </li>
                    @auth
                        @if(auth()->user()->role === 'pelanggan')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('riwayat*') ? 'active' : '' }}" href="{{ route('riwayat') }}">Reservasi Saya</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('favorit.*') ? 'active' : '' }}" href="{{ route('favorit.index') }}">Favorit</a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                    @guest
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-warning btn-sm fw-semibold" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @else
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="btn btn-warning btn-sm fw-semibold" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Panel Admin
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-1"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4 flex-grow-1">
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

    <footer class="bg-dark text-secondary py-4 mt-auto">
        <div class="container text-center small">
            <p class="mb-1 text-white fw-semibold">Studio Musik Positif</p>
            <p class="mb-0">&copy; {{ date('Y') }} Sistem Reservasi Studio Musik. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>