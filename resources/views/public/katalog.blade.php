@extends('layouts.app')

@section('title', 'Temukan Studio Anda - Studio Musik Positif')

@section('content')
<style>
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
        transition: transform 0.45s ease, filter 0.35s ease;
    }
    .studio-card-modern:hover .studio-img {
        transform: scale(1.04);
        filter: saturate(0.95) contrast(1);
    }
    .studio-card-modern .btn-dark {
        transition: all 0.25s ease;
    }
    .studio-card-modern .btn-dark:hover {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #181a1f;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 193, 7, 0.3);
    }
    .filter-box-modern {
        border-radius: 16px;
        background: #ffffff;
    }
    .badge-fasilitas {
        background-color: #f4f4f6;
        color: #3f3f46;
        font-weight: 500;
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        border-radius: 8px;
    }
    .pill-studio {
        border-radius: 50px;
        font-size: 0.8rem;
        padding: 0.35rem 0.9rem;
        transition: all 0.2s ease;
    }
</style>

<?php
    // 1. Saring studio berdasarkan Jumlah Personal (jika dipilih)
    $studioSesuaiPersonal = $daftarStudio->filter(function ($item) {
        return match (request('kapasitas')) {
            'kecil'  => $item->kapasitas <= 4,
            'sedang' => $item->kapasitas >= 5 && $item->kapasitas <= 7,
            'besar'  => $item->kapasitas >= 8,
            default  => true,
        };
    });

    // 2. Jika nama studio spesifik dipilih, tarif mengikuti studio tersebut; jika tidak, mengikuti Jumlah Personal
    $studioUntukTarif = request()->filled('cari')
        ? $studioSesuaiPersonal->filter(fn($s) => ($s->nama ?? $s->nama_studio) === request('cari'))
        : $studioSesuaiPersonal;

    $daftarTarifTersedia = $studioUntukTarif
        ->pluck('harga_per_jam')
        ->unique()
        ->sort()
        ->values();

    // 3. Daftar studio yang disarankan di dropdown pertama (mengikuti Jumlah Personal + Tarif per Jam)
    $studioDisarankan = $studioSesuaiPersonal->filter(function ($item) {
        return !request()->filled('harga_max') || $item->harga_per_jam <= (int) request('harga_max');
    });

    $adaFilterStudio = request()->filled('kapasitas') || request()->filled('harga_max');
    $adaFilterTarif  = request()->filled('kapasitas') || request()->filled('cari');

    $minTarif = $daftarTarifTersedia->first();
    $maxTarif = $daftarTarifTersedia->last();
    $ringkasanTarif = $daftarTarifTersedia->count() === 1
        ? 'Rp ' . number_format($minTarif, 0, ',', '.') . '/jam'
        : ($daftarTarifTersedia->count() > 1
            ? 'Rp ' . number_format($minTarif, 0, ',', '.') . ' – Rp ' . number_format($maxTarif, 0, ',', '.')
            : '');
?>

<!-- Header Halaman -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
    <div>
        <span class="badge bg-warning text-dark fw-semibold mb-2 px-3 py-1 rounded-pill">
            <i class="bi bi-music-note-beamed me-1"></i> Studio Musik Positif
        </span>
        <h2 class="fw-bold mb-1">Temukan studio Anda</h2>
        <p class="text-muted mb-0">Pilih jumlah personal atau tarif per jam untuk melihat saran studio yang sesuai.</p>
    </div>
    <?php if($studios->total() > 0): ?>
        <div class="text-muted small mt-2 mt-md-0">
            Menampilkan <strong>{{ $studios->firstItem() }}–{{ $studios->lastItem() }}</strong> dari <strong>{{ $studios->total() }}</strong> studio
        </div>
    <?php endif; ?>
</div>

<!-- Box Filter Dinamis -->
<div class="card filter-box-modern border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form id="formFilterStudio" action="{{ route('katalog') }}" method="GET" class="row g-3 align-items-end">
            <!-- 1. Studio yang Disarankan -->
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-dark">
                    <i class="bi {{ $adaFilterStudio ? 'bi-stars text-warning' : 'bi-music-note-list text-muted' }} me-1"></i>
                    {{ $adaFilterStudio ? 'Studio yang Disarankan (' . $studioDisarankan->count() . ')' : 'Pilih Studio' }}
                </label>
                <select name="cari" id="selectStudioDisarankan" class="form-select auto-submit-filter {{ $adaFilterStudio ? 'border-warning fw-semibold' : '' }}">
                    <option value="">
                        {{ $adaFilterStudio ? 'Semua rekomendasi (' . $studioDisarankan->count() . ' studio)' : 'Semua studio' }}
                    </option>
                    <?php foreach($studioDisarankan as $item): ?>
                        <?php $namaItem = $item->nama ?? $item->nama_studio; ?>
                        <option value="{{ $namaItem }}" {{ request('cari') == $namaItem ? 'selected' : '' }}>
                            {{ $namaItem }}
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- 2. Jumlah Personal -->
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-dark">
                    <i class="bi bi-people me-1 text-muted"></i> Jumlah Personal
                </label>
                <select name="kapasitas" id="filterKapasitas" class="form-select auto-submit-filter">
                    <option value="">Semua ukuran grup</option>
                    <option value="kecil" {{ request('kapasitas') == 'kecil' ? 'selected' : '' }}>Solo / Akustik (Maks. 4 orang)</option>
                    <option value="sedang" {{ request('kapasitas') == 'sedang' ? 'selected' : '' }}>Standar Band (5 – 7 orang)</option>
                    <option value="besar" {{ request('kapasitas') == 'besar' ? 'selected' : '' }}>Full Band / Orkestra (8+ orang)</option>
                </select>
            </div>

            <!-- 3. Tarif per Jam (Ikut Menampilkan Saran Otomatis) -->
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-dark">
                    <i class="bi {{ $adaFilterTarif ? 'bi-stars text-warning' : 'bi-tag text-muted' }} me-1"></i>
                    {{ $adaFilterTarif ? 'Tarif yang Disarankan (' . $daftarTarifTersedia->count() . ')' : 'Tarif per Jam' }}
                </label>
                <select name="harga_max" id="filterTarif" class="form-select auto-submit-filter {{ $adaFilterTarif ? 'border-warning fw-semibold' : '' }}">
                    <option value="">
                        {{ $adaFilterTarif && $ringkasanTarif ? 'Saran tarif (' . $ringkasanTarif . ')' : 'Semua tarif' }}
                    </option>
                    <?php foreach($daftarTarifTersedia as $tarif): ?>
                        <?php
                            $namaPemilikTarif = $studioUntukTarif
                                ->where('harga_per_jam', $tarif)
                                ->map(fn($s) => explode(' - ', $s->nama ?? $s->nama_studio)[0])
                                ->implode(', ');
                        ?>
                        <option value="{{ $tarif }}" {{ (string) request('harga_max') === (string) $tarif ? 'selected' : '' }}>
                            Maks. Rp {{ number_format($tarif, 0, ',', '.') }}/jam ({{ $namaPemilikTarif }})
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tombol Cari & Reset -->
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark fw-semibold w-100">
                    Cari
                </button>
                <a href="{{ route('katalog') }}" class="btn btn-outline-secondary" title="Reset Semua Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>

        <!-- Baris Pilihan Cepat Studio (Otomatis mengikuti saran) -->
        <?php if($studioDisarankan->isNotEmpty()): ?>
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-3 border-top">
                <span class="text-muted small me-1">
                    {{ $adaFilterStudio ? 'Rekomendasi cocok:' : 'Pilih cepat:' }}
                </span>
                <a href="{{ route('katalog', request()->except(['cari', 'page'])) }}"
                   class="btn btn-sm pill-studio {{ !request()->filled('cari') ? 'btn-dark' : 'btn-light border text-dark' }}">
                    Semua ({{ $studioDisarankan->count() }})
                </a>
                <?php foreach($studioDisarankan as $item): ?>
                    <?php $namaItem = $item->nama ?? $item->nama_studio; ?>
                    <a href="{{ route('katalog', array_merge(request()->except('page'), ['cari' => $namaItem])) }}"
                       class="btn btn-sm pill-studio {{ request('cari') == $namaItem ? 'btn-dark' : 'btn-light border text-dark' }}">
                        {{ $namaItem }}
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Daftar Kartu Studio -->
<div class="row g-4">
    <?php if($studios->isEmpty()): ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm text-center py-5 rounded-4">
                <div class="card-body py-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-search fs-3 text-muted"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Studio tidak ditemukan</h5>
                    <p class="text-muted small mb-4">Tidak ada ruangan yang cocok dengan kombinasi filter tersebut. Klik tombol di bawah untuk melihat semua studio.</p>
                    <a href="{{ route('katalog') }}" class="btn btn-dark px-4">Tampilkan Semua Studio</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach($studios as $studio): ?>
            <?php
                $fotoUtama = $studio->fotos->firstWhere('is_utama', true) ?? $studio->fotos->first();
                $namaStudio = $studio->nama ?? $studio->nama_studio;
                $avgRating = $studio->ulasans->avg('rating');
                $jumlahUlasan = $studio->ulasans->count();

                $fotoDefault = match(true) {
                    str_contains(strtolower($namaStudio), 'studio a') => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?auto=format&fit=crop&w=1000&q=80',
                    str_contains(strtolower($namaStudio), 'studio b') => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=1000&q=80',
                    str_contains(strtolower($namaStudio), 'studio c') => 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?auto=format&fit=crop&w=1000&q=80',
                    str_contains(strtolower($namaStudio), 'studio d') => 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=1000&q=80',
                    str_contains(strtolower($namaStudio), 'studio e') => 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?auto=format&fit=crop&w=1000&q=80',
                    default => 'https://images.unsplash.com/photo-1507838153414-b4b713384a76?auto=format&fit=crop&w=1000&q=80',
                };
            ?>
            <div class="col-md-4">
                <div class="card studio-card-modern h-100 shadow-sm border-0 overflow-hidden bg-white">
                    <div class="position-relative ratio ratio-16x9 bg-dark overflow-hidden">
                        <img src="{{ $fotoUtama ? asset('storage/' . $fotoUtama->path_foto) : $fotoDefault }}"
                             class="studio-img w-100 h-100 object-fit-cover"
                             alt="{{ $namaStudio }}">
                        <?php if($studio->status): ?>
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge {{ in_array(strtolower($studio->status), ['tersedia', 'aktif']) ? 'bg-success' : 'bg-secondary' }} shadow-sm px-2 py-1">
                                    {{ ucfirst($studio->status) }}
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h5 class="card-title fw-bold mb-0 text-dark">{{ $namaStudio }}</h5>
                            <span class="small fw-semibold text-dark text-nowrap ms-2">
                                <i class="bi bi-star-fill text-warning"></i>
                                {{ $jumlahUlasan > 0 ? number_format($avgRating, 1) : 'Baru' }}
                            </span>
                        </div>

                        <p class="text-muted small mb-2">
                            <i class="bi bi-people me-1"></i> Kapasitas {{ $studio->kapasitas }} orang
                            <?php if($jumlahUlasan > 0): ?>
                                &bull; {{ $jumlahUlasan }} ulasan
                            <?php endif; ?>
                        </p>

                        <div class="mb-3">
                            <span class="fw-bold text-dark fs-5">Rp {{ number_format($studio->harga_per_jam, 0, ',', '.') }}</span>
                            <span class="text-muted small">/ jam</span>
                        </div>

                        <?php if($studio->fasilitas->isNotEmpty()): ?>
                            <div class="d-flex flex-wrap gap-1 mb-4">
                                <?php foreach($studio->fasilitas->take(3) as $fas): ?>
                                    <span class="badge-fasilitas">{{ $fas->nama_fasilitas ?? $fas->nama }}</span>
                                <?php endforeach; ?>
                                <?php if($studio->fasilitas->count() > 3): ?>
                                    <span class="badge-fasilitas text-muted">+{{ $studio->fasilitas->count() - 3 }}</span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <p class="card-text small text-secondary mb-4">{{ Str::limit($studio->deskripsi, 85) }}</p>
                        <?php endif; ?>

                        <div class="mt-auto">
                            <a href="{{ route('studio.detail', $studio) }}" class="btn btn-dark fw-semibold w-100 rounded-3">
                                Lihat Detail & Jadwal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination -->
<div class="mt-5 d-flex justify-content-center">
    {{ $studios->links() }}
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formFilterStudio');
    document.querySelectorAll('.auto-submit-filter').forEach(function (select) {
        select.addEventListener('change', function () {
            if (select.id === 'filterKapasitas') {
                const selectStudio = document.getElementById('selectStudioDisarankan');
                const selectTarif = document.getElementById('filterTarif');
                if (selectStudio) selectStudio.value = '';
                if (selectTarif) selectTarif.value = '';
            } else if (select.id === 'filterTarif') {
                const selectStudio = document.getElementById('selectStudioDisarankan');
                if (selectStudio) selectStudio.value = '';
            }
            form.submit();
        });
    });
});
</script>
@endsection