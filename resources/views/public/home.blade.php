@extends('layouts.app')

@section('title', 'Beranda - Studio Musik Positif')

@section('content')
<div class="p-5 mb-4 bg-dark text-white rounded-3 shadow-sm">
    <div class="container-fluid py-3">
        <span class="badge bg-warning text-dark mb-2">Booking Online 24/7</span>
        <h1 class="display-5 fw-bold">Latihan & Rekaman Tanpa Ribet</h1>
        <p class="col-md-8 fs-5 text-light">
            Pilih studio musik terbaik lengkap dengan peralatan standar panggung. Cek ketersediaan jadwal secara real-time dan pesan langsung dari rumah.
        </p>
        <a href="{{ route('katalog') }}" class="btn btn-warning btn-lg fw-semibold text-dark">
            <i class="bi bi-search me-1"></i> Jelajahi Katalog Studio
        </a>
    </div>
</div>

@if($promos->isNotEmpty())
<div class="mb-5">
    <h4 class="fw-bold mb-3"><i class="bi bi-ticket-perforated-fill text-warning me-2"></i>Promo Spesial Aktif</h4>
    <div class="row g-3">
        @foreach($promos as $promo)
            <div class="col-md-4">
                <div class="card border-warning h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark fs-6">{{ $promo->kode_promo }}</span>
                            <span class="fw-bold text-success">
                                {{ $promo->tipe_diskon === 'persen' ? $promo->nilai_diskon . '%' : 'Rp ' . number_format($promo->nilai_diskon, 0, ',', '.') }} OFF
                            </span>
                        </div>
                        <h6 class="card-title fw-bold">{{ $promo->nama_promo }}</h6>
                        <p class="card-text small text-muted mb-1">{{ $promo->deskripsi }}</p>
                        @php
    $tglPromo = $promo->tanggal_berakhir ?? $promo->berlaku_sampai ?? null;
@endphp
@if($tglPromo)
    <small class="text-secondary">Berlaku s/d {{ \Carbon\Carbon::parse($tglPromo)->format('d M Y') }}</small>
@endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Studio Pilihan</h4>
    <a href="{{ route('katalog') }}" class="btn btn-outline-dark btn-sm">Lihat Semua <i class="bi bi-arrow-right"></i></a>
</div>

<div class="row g-4">
    @forelse($studios as $studio)
        <div class="col-md-4">
            <div class="card studio-card h-100 shadow-sm border-0">
                @php
                    $fotoUtama = $studio->fotos->firstWhere('is_utama', true) ?? $studio->fotos->first();
                @endphp
                <img src="{{ $fotoUtama ? asset('storage/' . $fotoUtama->path_foto) : 'https://placehold.co/600x400/212529/ffc107?text=' . urlencode($studio->nama_studio) }}"
                     class="card-img-top" alt="{{ $studio->nama_studio }}">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h5 class="card-title fw-bold mb-0">{{ $studio->nama_studio }}</h5>
                        <span class="badge bg-secondary">{{ ucfirst($studio->tipe_studio) }}</span>
                    </div>
                    <p class="text-muted small mb-2">
                        <i class="bi bi-people me-1"></i> Kapasitas {{ $studio->kapasitas }} orang
                        @if($studio->ulasans_avg_rating)
                            &bull; <i class="bi bi-star-fill text-warning"></i> {{ number_format($studio->ulasans_avg_rating, 1) }}
                        @endif
                    </p>
                    <p class="card-text small text-secondary flex-grow-1">{{ Str::limit($studio->deskripsi, 90) }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                        <span class="fw-bold text-primary">Rp {{ number_format($studio->harga_per_jam, 0, ',', '.') }} <small class="text-muted fw-normal">/jam</small></span>
                        <a href="{{ route('studio.detail', $studio) }}" class="btn btn-dark btn-sm">Detail & Jadwal</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">Belum ada studio yang tersedia saat ini.</div>
        </div>
    @endforelse
</div>
@endsection