@extends('layouts.app')

@section('title', 'Beranda - Studio Musik Positif')

@section('content')
<style>
    /* Hero Banner dengan Background Studio Kalem & Gradasi Modern */
    .hero-banner-modern {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        background: linear-gradient(125deg, rgba(20, 22, 27, 0.94) 0%, rgba(24, 26, 31, 0.86) 60%, rgba(45, 48, 56, 0.78) 100%),
                    url('https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?auto=format&fit=crop&w=1400&q=80') center/cover no-repeat;
        box-shadow: 0 14px 32px rgba(0, 0, 0, 0.14);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hero-banner-modern:hover {
        box-shadow: 0 18px 38px rgba(0, 0, 0, 0.18);
    }

    /* Efek Tombol Utama Jelajahi Katalog */
    .btn-hero-cta {
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 6px 18px rgba(255, 193, 7, 0.28);
    }
    .btn-hero-cta:hover {
        transform: translateY(-3px);
        background-color: #ffca2c;
        box-shadow: 0 10px 24px rgba(255, 193, 7, 0.42);
    }
    .btn-hero-cta i {
        display: inline-block;
        transition: transform 0.25s ease;
    }
    .btn-hero-cta:hover i {
        transform: scale(1.15) rotate(-6deg);
    }

    /* Efek Kartu Promo Interaktif */
    .promo-card-modern {
        border-radius: 16px;
        border: 1.5px solid rgba(255, 193, 7, 0.45);
        background: #ffffff;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .promo-card-modern:hover {
        transform: translateY(-5px);
        border-color: #ffc107;
        box-shadow: 0 12px 24px rgba(255, 193, 7, 0.16) !important;
    }
    .btn-copy-promo {
        border-radius: 8px;
        font-size: 0.75rem;
        transition: all 0.2s ease;
    }

    /* Efek Kartu Studio Pilihan (Tone Kalem & Selaras dengan Katalog) */
    .studio-card-modern {
        border-radius: 16px;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .studio-card-modern:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.1) !important;
    }
    .studio-card-modern .studio-img {
        filter: saturate(0.85) contrast(0.96);
        transition: transform 0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94), filter 0.35s ease;
    }
    .studio-card-modern:hover .studio-img {
        transform: scale(1.05);
        filter: saturate(0.95) contrast(1);
    }
    .badge-fasilitas {
        background-color: #f4f4f6;
        color: #3f3f46;
        font-weight: 500;
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        border-radius: 8px;
    }
    .btn-detail-modern {
        border-radius: 10px;
        transition: all 0.25s ease;
    }
    .btn-detail-modern:hover {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #181a1f;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 193, 7, 0.3);
    }
</style>

<!-- Hero Banner -->
<div class="hero-banner-modern p-4 p-md-5 mb-5 text-white">
    <div class="container-fluid py-2 py-md-3">
        <span class="badge bg-warning text-dark fw-semibold px-3 py-1 rounded-pill mb-3">
            <i class="bi bi-lightning-charge-fill me-1"></i> Booking Online 24/7
        </span>
        <h1 class="display-5 fw-bold mb-3">Latihan & Rekaman Tanpa Ribet</h1>
        <p class="col-md-8 fs-5 text-light opacity-75 mb-4">
            Pilih studio musik terbaik lengkap dengan peralatan standar panggung. Cek ketersediaan jadwal secara real-time dan pesan langsung dari rumah.
        </p>
        <a href="{{ route('katalog') }}" class="btn btn-warning btn-lg fw-bold text-dark btn-hero-cta">
            <i class="bi bi-search me-2"></i> Jelajahi Katalog Studio
        </a>
    </div>
</div>

<!-- Promo Spesial Aktif -->
@if($promos->isNotEmpty())
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-ticket-perforated-fill text-warning me-2"></i>Promo Spesial Aktif
        </h4>
        <span class="text-muted small d-none d-sm-inline">Klik kode untuk menyalin</span>
    </div>
    <div class="row g-3">
        @foreach($promos as $promo)
            @php
                $kodePromo = $promo->kode ?? $promo->kode_promo;
                $tipeDiskon = $promo->tipe_potongan ?? $promo->tipe_diskon;
                $nilaiDiskon = $promo->potongan ?? $promo->nilai_diskon ?? 0;
                $tglPromo = $promo->berlaku_sampai ?? $promo->tanggal_berakhir ?? null;
            @endphp
            <div class="col-md-4">
                <div class="card promo-card-modern h-100 shadow-sm">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-warning text-dark fw-bold fs-6 px-3 py-2 rounded-3">{{ $kodePromo }}</span>
                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm btn-copy-promo"
                                            onclick="salinKodePromo('{{ $kodePromo }}', this)"
                                            title="Salin kode promo">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                                <span class="fw-bold text-success fs-5">
                                    {{ $tipeDiskon === 'persen' ? $nilaiDiskon . '%' : 'Rp ' . number_format($nilaiDiskon, 0, ',', '.') }} OFF
                                </span>
                            </div>
                            <p class="card-text small text-muted mb-3">
                                Gunakan kode promo <strong>{{ $kodePromo }}</strong> saat melakukan reservasi studio untuk mendapatkan potongan harga.
                            </p>
                        </div>
                        @if($tglPromo)
                            <div class="pt-2 border-top">
                                <small class="text-secondary">
                                    <i class="bi bi-calendar-event me-1"></i>Berlaku s/d {{ \Carbon\Carbon::parse($tglPromo)->format('d M Y') }}
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Studio Pilihan -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0">Studio Pilihan</h4>
        <p class="text-muted small mb-0">Ruangan favorit dengan akustik dan peralatan siap pakai</p>
    </div>
    <a href="{{ route('katalog') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-semibold">
        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
    </a>
</div>

<div class="row g-4">
    @forelse($studios as $studio)
        @php
            $namaStudio = $studio->nama ?? $studio->nama_studio;
            $fotoUtama = $studio->fotos->firstWhere('is_utama', true) ?? $studio->fotos->first();
            $avgRating = $studio->ulasans_avg_rating ?? $studio->ulasans->avg('rating');
            $jumlahUlasan = $studio->ulasans->count();

            $fotoDefault = match(true) {
                str_contains(strtolower($namaStudio), 'studio a') => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?auto=format&fit=crop&w=1000&q=80',
                str_contains(strtolower($namaStudio), 'studio b') => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=1000&q=80',
                str_contains(strtolower($namaStudio), 'studio c') => 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?auto=format&fit=crop&w=1000&q=80',
                str_contains(strtolower($namaStudio), 'studio d') => 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=1000&q=80',
                str_contains(strtolower($namaStudio), 'studio e') => 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?auto=format&fit=crop&w=1000&q=80',
                default => 'https://images.unsplash.com/photo-1507838153414-b4b713384a76?auto=format&fit=crop&w=1000&q=80',
            };
        @endphp
        <div class="col-md-4">
            <div class="card studio-card-modern h-100 shadow-sm border-0 overflow-hidden bg-white">
                <div class="position-relative ratio ratio-16x9 bg-dark overflow-hidden">
                    <img src="{{ $fotoUtama ? asset('storage/' . $fotoUtama->path_foto) : $fotoDefault }}"
                         class="studio-img w-100 h-100 object-fit-cover"
                         alt="{{ $namaStudio }}">
                    @if($studio->status)
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge {{ in_array(strtolower($studio->status), ['tersedia', 'aktif']) ? 'bg-success' : 'bg-secondary' }} shadow-sm px-2 py-1">
                                {{ ucfirst($studio->status) }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h5 class="card-title fw-bold mb-0 text-dark">{{ $namaStudio }}</h5>
                        <span class="small fw-semibold text-dark text-nowrap ms-2">
                            <i class="bi bi-star-fill text-warning"></i>
                            {{ $avgRating ? number_format($avgRating, 1) : 'Baru' }}
                        </span>
                    </div>

                    <p class="text-muted small mb-2">
                        <i class="bi bi-people me-1"></i> Kapasitas {{ $studio->kapasitas }} orang
                        @if($jumlahUlasan > 0)
                            &bull; {{ $jumlahUlasan }} ulasan
                        @endif
                    </p>

                    <div class="mb-3">
                        <span class="fw-bold text-dark fs-5">Rp {{ number_format($studio->harga_per_jam, 0, ',', '.') }}</span>
                        <span class="text-muted small">/ jam</span>
                    </div>

                    @if($studio->fasilitas->isNotEmpty())
                        <div class="d-flex flex-wrap gap-1 mb-4">
                            @foreach($studio->fasilitas->take(3) as $fas)
                                <span class="badge-fasilitas">{{ $fas->nama_fasilitas ?? $fas->nama }}</span>
                            @endforeach
                            @if($studio->fasilitas->count() > 3)
                                <span class="badge-fasilitas text-muted">+{{ $studio->fasilitas->count() - 3 }}</span>
                            @endif
                        </div>
                    @else
                        <p class="card-text small text-secondary mb-4">{{ Str::limit($studio->deskripsi, 85) }}</p>
                    @endif

                    <div class="mt-auto">
                        <a href="{{ route('studio.detail', $studio) }}" class="btn btn-dark fw-semibold w-100 btn-detail-modern">
                            Lihat Detail & Jadwal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info rounded-4">Belum ada studio yang tersedia saat ini.</div>
        </div>
    @endforelse
</div>

<script>
function salinKodePromo(kode, btn) {
    navigator.clipboard.writeText(kode).then(function () {
        const iconAsli = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-lg text-success"></i>';
        btn.classList.replace('btn-outline-secondary', 'btn-light');
        setTimeout(function () {
            btn.innerHTML = iconAsli;
            btn.classList.replace('btn-light', 'btn-outline-secondary');
        }, 1500);
    });
}
</script>
@endsection