@extends('layouts.app')

@section('title', 'Katalog Studio - Studio Musik Positif')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Katalog Studio Musik</h2>
        <p class="text-muted mb-0">Temukan ruangan latihan atau rekaman yang sesuai dengan kebutuhan band Anda.</p>
    </div>
</div>

<!-- Form Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('katalog') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Cari Nama Studio</label>
                <input type="text" name="cari" class="form-control" placeholder="Contoh: Studio A, Recording..." value="{{ request('cari') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Kapasitas Minimal (Orang)</label>
                <input type="number" name="kapasitas" class="form-control" min="1" placeholder="Contoh: 5" value="{{ request('kapasitas') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Harga Maksimal (/Jam)</label>
                <input type="number" name="harga_max" class="form-control" step="10000" placeholder="Contoh: 150000" value="{{ request('harga_max') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
                @if(request()->anyFilled(['cari', 'kapasitas', 'harga_max']))
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
            <div class="card studio-card h-100 shadow-sm border-0">
                @php
                    $fotoUtama = $studio->fotos->firstWhere('is_utama', true) ?? $studio->fotos->first();
                    $namaStudio = $studio->nama ?? $studio->nama_studio;
                    $avgRating = $studio->ulasans->avg('rating');
                @endphp
                <img src="{{ $fotoUtama ? asset('storage/' . $fotoUtama->path_foto) : 'https://placehold.co/600x400/212529/ffc107?text=' . urlencode($namaStudio) }}"
                     class="card-img-top" alt="{{ $namaStudio }}">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h5 class="card-title fw-bold mb-0">{{ $namaStudio }}</h5>
                        @if($studio->status)
                            <span class="badge {{ $studio->status === 'tersedia' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($studio->status) }}</span>
                        @endif
                    </div>
                    <p class="text-muted small mb-2">
                        <i class="bi bi-people me-1"></i> Kapasitas {{ $studio->kapasitas }} orang
                        @if($avgRating)
                            &bull; <i class="bi bi-star-fill text-warning"></i> {{ number_format($avgRating, 1) }} ({{ $studio->ulasans->count() }})
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
                        <span class="fw-bold text-primary">Rp {{ number_format($studio->harga_per_jam, 0, ',', '.') }} <small class="text-muted fw-normal">/jam</small></span>
                        <a href="{{ route('studio.detail', $studio) }}" class="btn btn-dark btn-sm">Detail & Jadwal</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-warning text-center py-4">
                <i class="bi bi-exclamation-circle fs-3 d-block mb-2"></i>
                Tidak ada studio yang sesuai dengan kriteria pencarian Anda.
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $studios->links() }}
</div>
@endsection