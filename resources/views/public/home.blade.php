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
            @php
                $kodePromo = $promo->kode ?? $promo->kode_promo;
                $tipeDiskon = $promo->tipe_potongan ?? $promo->tipe_diskon;
                $nilaiDiskon = $promo->potongan ?? $promo->nilai_diskon ?? 0;
                $tglPromo = $promo->berlaku_sampai ?? $promo->tanggal_berakhir ?? null;
            @endphp
            <div class="col-md-4">
                <div class="card border-warning h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2">{{ $kodePromo }}</span>
                                <span class="fw-bold text-success fs-5">
                                    {{ $tipeDiskon === 'persen' ? $nilaiDiskon . '%' : 'Rp ' . number_format($nilaiDiskon, 0, ',', '.') }} OFF
                                </span>
                            </div>
                            <p class="card-text small text-muted mb-2">
                                Gunakan kode promo <strong>{{ $kodePromo }}</strong> saat melakukan reservasi studio untuk mendapatkan potongan harga.
                            </p>
                        </div>
                        @if($tglPromo)
                            <div class="pt-2 border-top">
                                <small class="text-secondary"><i class="bi bi-calendar-event me-1"></i>Berlaku s/d {{ \Carbon\Carbon::parse($tglPromo)->format('d M Y') }}</small>
                            </div>
                        @endif
                    </div>
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
        @php
            $namaStudio = $studio->nama ?? $studio->nama_studio;
            $fotoUtama = $studio->fotos->firstWhere('is_utama', true) ?? $studio->fotos->first();
        @endphp
        <div class="col-md-4">
            <div class="card studio-card h-100 shadow-sm border-0">
                <img src="{{ $fotoUtama ? asset('storage/' . $fotoUtama->path_foto) : 'https://placehold.co/600x400/212529/ffc107?text=' . urlencode($namaStudio) }}"
                     class="card-img-top" alt="{{ $namaStudio }}">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h5 class="card-title fw-bold mb-0">{{ $namaStudio }}</h5>
                        @if(!empty($studio->tipe_studio))
                            <span class="badge bg-secondary">{{ ucfirst($studio->tipe_studio) }}</span>
                        @endif
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