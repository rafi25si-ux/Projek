@extends('layouts.app')

@section('title', 'Studio Favorit Saya - Studio Musik Positif')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Studio Favorit Saya</h3>
            <p class="text-muted mb-0">Daftar ruangan studio musik pilihan yang Anda simpan.</p>
        </div>
        <a href="{{ route('katalog') }}" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-grid me-1"></i> Jelajahi Katalog
        </a>
    </div>

    @if($favorits->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body py-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-circle mb-3" style="width: 72px; height: 72px;">
                    <i class="bi bi-heart fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Belum ada daftar studio favorit Anda</h5>
                <p class="text-muted small mb-4">Simpan studio favorit Anda dari halaman katalog agar lebih mudah dipesan kapan saja.</p>
                <a href="{{ route('katalog') }}" class="btn btn-dark px-4">
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
                        $foto = $studio->fotos->first();
                        $fotoUrl = $foto
                            ? asset('storage/' . ($foto->path ?? $foto->foto_path ?? $foto->url))
                            : 'https://placehold.co/600x400/1a1d24/ffc107?text=Studio+Musik+Positif';
                        $avgRating = round($studio->ulasans->avg('rating') ?? 0, 1);
                        $jumlahUlasan = $studio->ulasans->count();
                    @endphp

                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden">
                            <div class="position-relative">
                                <img src="{{ $fotoUrl }}"
                                     class="card-img-top"
                                     alt="{{ $studio->nama }}"
                                     style="height: 210px; object-fit: cover;">

                                <!-- Tombol Hapus dari Favorit -->
                                <form action="{{ route('favorit.toggle', $studio) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-light btn-sm rounded-circle shadow-sm text-danger"
                                            title="Hapus dari favorit"
                                            onclick="return confirm('Hapus studio ini dari daftar favorit?')">
                                        <i class="bi bi-heart-fill"></i>
                                    </button>
                                </form>

                                @if($studio->kategori ?? false)
                                    <span class="badge bg-dark position-absolute bottom-0 start-0 m-2">
                                        {{ $studio->kategori }}
                                    </span>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h5 class="card-title fw-bold mb-0">{{ $studio->nama }}</h5>
                                    <span class="small fw-semibold text-warning text-nowrap ms-2">
                                        <i class="bi bi-star-fill"></i>
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
                                            <span class="badge bg-light text-dark border fw-normal">
                                                {{ $fas->nama }}
                                            </span>
                                        @endforeach
                                        @if($studio->fasilitas->count() > 3)
                                            <span class="badge bg-light text-muted border fw-normal">
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
                                        <a href="{{ route('studio.detail', $studio) }}" class="btn btn-outline-dark btn-sm">
                                            Detail
                                        </a>
                                        <a href="{{ route('booking.create', $studio) }}" class="btn btn-warning text-dark fw-semibold btn-sm">
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