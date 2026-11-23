@extends('layouts.app')

@section('title', ($studio->nama ?? $studio->nama_studio) . ' - Studio Musik Positif')

@section('content')
<style>
    .detail-gallery-card {
        border-radius: 18px;
        overflow: hidden;
    }
    .detail-gallery-img {
        height: 410px;
        object-fit: cover;
        filter: saturate(0.85) contrast(0.96);
        transition: transform 0.5s ease, filter 0.35s ease;
    }
    .detail-gallery-card:hover .detail-gallery-img {
        transform: scale(1.03);
        filter: saturate(0.95) contrast(1);
    }
    .detail-card-modern {
        border-radius: 18px;
    }
    .btn-booking-modern {
        border-radius: 12px;
        transition: all 0.25s ease;
        box-shadow: 0 6px 16px rgba(255, 193, 7, 0.28);
    }
    .btn-booking-modern:hover {
        transform: translateY(-2px);
        background-color: #ffca2c;
        box-shadow: 0 10px 22px rgba(255, 193, 7, 0.42);
    }
    .btn-favorit-modern {
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    .btn-favorit-modern:hover {
        transform: translateY(-1px);
    }
</style>

@php
    $namaStudio = $studio->nama ?? $studio->nama_studio;
    $avgRating = $studio->ulasans->avg('rating');

    $fotoDefault = match(true) {
        str_contains(strtolower($namaStudio), 'studio a') => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?auto=format&fit=crop&w=1200&q=80',
        str_contains(strtolower($namaStudio), 'studio b') => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=1200&q=80',
        str_contains(strtolower($namaStudio), 'studio c') => 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?auto=format&fit=crop&w=1200&q=80',
        str_contains(strtolower($namaStudio), 'studio d') => 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=1200&q=80',
        str_contains(strtolower($namaStudio), 'studio e') => 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?auto=format&fit=crop&w=1200&q=80',
        default => 'https://images.unsplash.com/photo-1507838153414-b4b713384a76?auto=format&fit=crop&w=1200&q=80',
    };
@endphp

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('katalog') }}" class="text-decoration-none">Katalog</a></li>
        <li class="breadcrumb-item active">{{ $namaStudio }}</li>
    </ol>
</nav>

<div class="row g-4">
    <!-- Kolom Kiri: Galeri, Deskripsi, Fasilitas, Ulasan -->
    <div class="col-lg-8">
        <!-- Galeri Foto -->
        <div class="card detail-gallery-card border-0 shadow-sm mb-4 bg-dark">
            @if($studio->fotos->isNotEmpty())
                <div id="carouselStudio" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($studio->fotos as $index => $foto)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $foto->path_foto) }}" class="d-block w-100 detail-gallery-img" alt="{{ $namaStudio }}">
                            </div>
                        @endforeach
                    </div>
                    @if($studio->fotos->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselStudio" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselStudio" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    @endif
                </div>
            @else
                <img src="{{ $fotoDefault }}" class="w-100 detail-gallery-img" alt="{{ $namaStudio }}">
            @endif
        </div>

        <!-- Informasi Detail -->
        <div class="card detail-card-modern border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h2 class="fw-bold mb-1">{{ $namaStudio }}</h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-people-fill me-1"></i> Kapasitas Maksimal: <strong>{{ $studio->kapasitas }} Orang</strong>
                            @if($avgRating)
                                &nbsp;&bull;&nbsp; <i class="bi bi-star-fill text-warning"></i> <strong>{{ number_format($avgRating, 1) }}</strong> ({{ $studio->ulasans->count() }} ulasan)
                            @endif
                        </p>
                    </div>
                    @auth
                        @if(auth()->user()->role === 'pelanggan')
                            @php
                                $isFavorit = \App\Models\Favorit::where('user_id', auth()->id())
                                    ->where('studio_id', $studio->id)
                                    ->exists();
                            @endphp
                            <form action="{{ route('favorit.toggle', $studio) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn {{ $isFavorit ? 'btn-danger' : 'btn-outline-secondary' }} btn-sm btn-favorit-modern px-3">
                                    <i class="bi {{ $isFavorit ? 'bi-heart-fill' : 'bi-heart' }} me-1"></i>
                                    {{ $isFavorit ? 'Difavoritkan' : 'Simpan Favorit' }}
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>

                <hr>
                <h5 class="fw-bold">Deskripsi Studio</h5>
                <p class="text-secondary">{{ $studio->deskripsi }}</p>

                <h5 class="fw-bold mt-4">Fasilitas & Peralatan</h5>
                <div class="row g-2 mt-1">
                    @forelse($studio->fasilitas as $fas)
                        <div class="col-md-6">
                            <div class="p-2 px-3 border rounded-3 bg-light d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>{{ $fas->nama_fasilitas ?? $fas->nama }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-muted small">Belum ada daftar fasilitas yang dicantumkan.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Ulasan Pelanggan -->
        <div class="card detail-card-modern border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Ulasan Pelanggan ({{ $studio->ulasans->count() }})</h5>
                @forelse($studio->ulasans as $ulasan)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $ulasan->user->name ?? 'Pelanggan' }}</strong>
                            <span class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $ulasan->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </span>
                        </div>
                        <small class="text-muted">{{ $ulasan->created_at?->format('d M Y') }}</small>
                        <p class="mb-0 mt-1 text-secondary">{{ $ulasan->komentar }}</p>
                    </div>
                @empty
                    <p class="text-muted mb-0">Belum ada ulasan untuk studio ini.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Cek Jadwal & Tombol Booking -->
    <div class="col-lg-4">
        <div class="card detail-card-modern border-0 shadow-sm sticky-top" style="top: 20px;">
            <div class="card-body p-4">
                <div class="mb-3">
                    <span class="text-muted small">Harga Sewa</span>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($studio->harga_per_jam, 0, ',', '.') }} <small class="fs-6 text-muted fw-normal">/ jam</small></h3>
                </div>

                <hr>

                <form action="{{ route('studio.detail', $studio) }}" method="GET" class="mb-3">
                    <label class="form-label fw-semibold small">Cek Jadwal Terisi pada Tanggal:</label>
                    <div class="input-group">
                        <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" min="{{ now()->toDateString() }}">
                        <button type="submit" class="btn btn-outline-dark">Cek</button>
                    </div>
                </form>

                <div class="mb-4">
                    <h6 class="fw-semibold small text-uppercase text-muted">Jadwal Terisi ({{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }})</h6>
                    @if($jadwalTerisi->isEmpty())
                        <div class="alert alert-success py-2 small mb-0 rounded-3">
                            <i class="bi bi-check-circle me-1"></i> Semua jam masih kosong pada tanggal ini!
                        </div>
                    @else
                        <ul class="list-group list-group-flush small">
                            @foreach($jadwalTerisi as $item)
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span><i class="bi bi-clock text-danger me-1"></i> {{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}</span>
                                    <span class="badge bg-danger">Terisi</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                @auth
                    @if(auth()->user()->role === 'pelanggan')
                        <a href="{{ route('booking.create', ['studio' => $studio->id, 'tanggal' => $tanggal]) }}" class="btn btn-warning w-100 fw-bold py-2 text-dark btn-booking-modern">
                            <i class="bi bi-calendar-check me-1"></i> Booking Studio Sekarang
                        </a>
                    @else
                        <div class="alert alert-secondary small mb-0 text-center">Login sebagai pelanggan untuk melakukan booking.</div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-dark w-100 fw-semibold py-2 rounded-3">
                        Login untuk Booking
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection