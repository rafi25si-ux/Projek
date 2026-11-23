@extends('layouts.app')

@section('title', 'Studio Favorit Saya - Studio Musik Positif')

@section('content')
<style>
    .favorit-card-modern {
        border-radius: 16px;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .favorit-card-modern:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.1) !important;
    }
    .favorit-card-modern .studio-img {
        height: 215px;
        object-fit: cover;
        filter: saturate(0.85) contrast(0.96);
        transition: transform 0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94), filter 0.35s ease;
    }
    .favorit-card-modern:hover .studio-img {
        transform: scale(1.05);
        filter: saturate(0.95) contrast(1);
    }
    .btn-heart-modern {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .btn-heart-modern:hover {
        transform: scale(1.12);
        background-color: #fff5f5;
    }
    .badge-fasilitas {
        background-color: #f4f4f6;
        color: #3f3f46;
        font-weight: 500;
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        border-radius: 8px;
    }
    .btn-booking-sm {
        border-radius: 9px;
        transition: all 0.2s ease;
    }
    .btn-booking-sm:hover {
        transform: translateY(-1px);
        background-color: #ffca2c;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.35);
    }
</style>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="badge bg-warning text-dark fw-semibold mb-2 px-3 py-1 rounded-pill">
                <i class="bi bi-heart-fill text-danger me-1"></i> Koleksi Pilihan
            </span>
            <h3 class="fw-bold mb-1">Studio Favorit Saya</h3>
            <p class="text-muted mb-0">Daftar ruangan studio musik pilihan yang Anda simpan.</p>
        </div>
        <a href="{{ route('katalog') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-semibold">
            <i class="bi bi-grid me-1"></i> Jelajahi Katalog
        </a>
    </div>

    @if($favorits->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5 rounded-4">
            <div class="card-body py-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-circle mb-3" style="width: 72px; height: 72px;">
                    <i class="bi bi-heart fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Belum ada daftar studio favorit Anda</h5>
                <p class="text-muted small mb-4">Simpan studio favorit Anda dari halaman katalog agar lebih mudah dipesan kapan saja.</p>
                <a href="{{ route('katalog') }}" class="btn btn-dark px-4 rounded-3">
                    Cari Studio Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($favorits as $fav)
                @php
                    $studio = $fav->studio;
                @endphp

                @if($studio)
                    @php
                        $namaStudio = $studio->nama ?? $studio->nama_studio;
                        $foto = $studio->fotos->firstWhere('is_utama', true) ?? $studio->fotos->first();
                        $pathFoto = $foto ? ($foto->path_foto ?? $foto->path ?? $foto->foto_path ?? $foto->url) : null;

                        $fotoDefault = match(true) {
                            str_contains(strtolower($namaStudio), 'studio a') => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?auto=format&fit=crop&w=1000&q=80',
                            str_contains(strtolower($namaStudio), 'studio b') => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=1000&q=80',
                            str_contains(strtolower($namaStudio), 'studio c') => 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?auto=format&fit=crop&w=1000&q=80',
                            str_contains(strtolower($namaStudio), 'studio d') => 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=1000&q=80',
                            str_contains(strtolower($namaStudio), 'studio e') => 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?auto=format&fit=crop&w=1000&q=80',
                            default => 'https://images.unsplash.com/photo-1507838153414-b4b713384a76?auto=format&fit=crop&w=1000&q=80',
                        };

                        $fotoUrl = $pathFoto ? asset('storage/' . $pathFoto) : $fotoDefault;
                        $avgRating = round($studio->ulasans->avg('rating') ?? 0, 1);
                        $jumlahUlasan = $studio->ulasans->count();
                    @endphp

                    <div class="col-md-6 col-lg-4">
                        <div class="card favorit-card-modern h-100 border-0 shadow-sm overflow-hidden bg-white">
                            <div class="position-relative bg-dark overflow-hidden">
                                <img src="{{ $fotoUrl }}"
                                     class="card-img-top studio-img w-100"
                                     alt="{{ $namaStudio }}">

                                <!-- Tombol Hapus dari Favorit -->
                                <form action="{{ route('favorit.toggle', $studio) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-light btn-sm rounded-circle shadow-sm text-danger btn-heart-modern"
                                            title="Hapus dari favorit"
                                            onclick="return confirm('Hapus studio ini dari daftar favorit?')">
                                        <i class="bi bi-heart-fill"></i>
                                    </button>
                                </form>

                                @if($studio->kategori ?? false)
                                    <span class="badge bg-dark bg-opacity-75 position-absolute bottom-0 start-0 m-2 px-2 py-1">
                                        {{ $studio->kategori }}
                                    </span>
                                @endif
                            </div>

                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h5 class="card-title fw-bold mb-0 text-dark">{{ $namaStudio }}</h5>
                                    <span class="small fw-semibold text-dark text-nowrap ms-2">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        {{ $jumlahUlasan > 0 ? $avgRating . ' (' . $jumlahUlasan . ')' : 'Baru' }}
                                    </span>
                                </div>

                                <p class="text-muted small mb-3">
                                    {{ \Illuminate\Support\Str::limit($studio->deskripsi, 90) }}
                                </p>

                                <!-- Fasilitas Singkat -->
                                @if($studio->fasilitas->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-1 mb-3">
                                        @foreach($studio->fasilitas->take(3) as $fas)
                                            <span class="badge-fasilitas">
                                                {{ $fas->nama_fasilitas ?? $fas->nama }}
                                            </span>
                                        @endforeach
                                        @if($studio->fasilitas->count() > 3)
                                            <span class="badge-fasilitas text-muted">
                                                +{{ $studio->fasilitas->count() - 3 }} lainnya
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold text-dark fs-6">
                                            Rp {{ number_format($studio->harga_per_jam, 0, ',', '.') }}
                                        </span>
                                        <small class="text-muted">/ jam</small>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('studio.detail', $studio) }}" class="btn btn-outline-dark btn-sm rounded-3">
                                            Detail
                                        </a>
                                        <a href="{{ route('booking.create', $studio) }}" class="btn btn-warning text-dark fw-semibold btn-sm btn-booking-sm">
                                            Booking
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection