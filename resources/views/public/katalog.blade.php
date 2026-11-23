@extends('layouts.app')

@section('title', 'Katalog Studio - Studio Musik Positif')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Katalog Studio Musik</h2>
        <p class="text-muted mb-0">Temukan ruangan latihan atau rekaman yang sesuai dengan kebutuhan band Anda.</p>
    </div>
</div>

<!-- Form Filter Kapasitas -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('katalog') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-9 col-lg-10">
                <label class="form-label fw-semibold small">Kapasitas Minimal (Orang)</label>
                <input type="number" name="kapasitas" class="form-control" min="1" placeholder="Masukkan jumlah orang (Contoh: 5)" value="{{ request('kapasitas') }}">
            </div>
            <div class="col-md-3 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
                @if(request()->filled('kapasitas'))
                    <a href="{{ route('katalog') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Daftar Studio -->
<div class="row g-4">
    @forelse($studios as $studio)
        <div class="col-md-4">
            <div class="card studio-card h-100 shadow-sm border-0 overflow-hidden">
                @php
                    $fotoUtama = $studio->fotos->firstWhere('is_utama', true) ?? $studio->fotos->first();
                    $namaStudio = $studio->nama ?? $studio->nama_studio;
                    $avgRating = $studio->ulasans->avg('rating');
                    $jumlahUlasan = $studio->ulasans->count();

                    // Foto studio musik otomatis jika admin belum upload foto ke database
                    $fotoDefault = match(true) {
                        str_contains(strtolower($namaStudio), 'studio a') => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?auto=format&fit=crop&w=800&q=80',
                        str_contains(strtolower($namaStudio), 'studio b') => 'https://images.unsplash.com/photo-1519892300165-cb5542fb47c7?auto=format&fit=crop&w=800&q=80',
                        str_contains(strtolower($namaStudio), 'studio c') => 'https://images.unsplash.com/photo-1598653222000-6b7b7a552625?auto=format&fit=crop&w=800&q=80',
                        default => 'https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?auto=format&fit=crop&w=800&q=80',
                    };
                @endphp

                <div class="ratio ratio-16x9 bg-dark">
                    <img src="{{ $fotoUtama ? asset('storage/' . $fotoUtama->path_foto) : $fotoDefault }}"
                         class="card-img-top w-100 h-100 object-fit-cover"
                         alt="{{ $namaStudio }}">
                </div>

                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h5 class="card-title fw-bold mb-0">{{ $namaStudio }}</h5>
                        @if($studio->status)
                            <span class="badge {{ $studio->status === 'tersedia' || $studio->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($studio->status) }}</span>
                        @endif
                    </div>
                    <p class="text-muted small mb-2">
                        <i class="bi bi-people me-1"></i> Kapasitas {{ $studio->kapasitas }} orang
                        @if($jumlahUlasan > 0)
                            &bull; <i class="bi bi-star-fill text-warning"></i> {{ number_format($avgRating, 1) }} ({{ $jumlahUlasan }})
                        @else
                            &bull; <span class="text-muted">Belum ada ulasan</span>
                        @endif
                    </p>
                    <p class="card-text small text-secondary flex-grow-1">{{ Str::limit($studio->deskripsi, 100) }}</p>

                    @if($studio->fasilitas->isNotEmpty())
                        <div class="mb-3">
                            @foreach($studio->fasilitas->take(3) as $fas)
                                <span class="badge bg-light text-dark border me-1 mb-1">{{ $fas->nama_fasilitas ?? $fas->nama }}</span>
                            @endforeach
                            @if($studio->fasilitas->count() > 3)
                                <span class="badge bg-light text-muted border">+{{ $studio->fasilitas->count() - 3 }} lainnya</span>
                            @endif
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">Rp {{ number_format($studio->harga_per_jam, 0, ',', '.') }} <small class="text-muted fw-normal">/jam</small></span>
                        <a href="{{ route('studio.detail', $studio) }}" class="btn btn-dark btn-sm">Detail & Jadwal</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-warning text-center py-4 border-0 rounded-3 shadow-sm">
                <i class="bi bi-exclamation-circle fs-3 d-block mb-2"></i>
                Tidak ada studio dengan kapasitas minimal tersebut.
            </div>
        </div>
    @endforelse
</div>

<div class="mt-5 d-flex justify-content-center">
    {{ $studios->links() }}
</div>
@endsection