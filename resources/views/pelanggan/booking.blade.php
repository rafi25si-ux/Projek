@extends('layouts.app')

@section('title', 'Booking ' . ($studio->nama ?? $studio->nama_studio) . ' - Studio Musik Positif')

@push('styles')
<style>
    .luxury-panel {
        background: linear-gradient(160deg, #1a1d24 0%, #12141a 55%, #0d0f13 100%);
        border: 1px solid rgba(255, 193, 7, 0.22);
        border-radius: 1.25rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.06);
        color: #f8f9fa;
        overflow: hidden;
    }
    .luxury-header {
        background: radial-gradient(circle at top right, rgba(255, 193, 7, 0.16) 0%, rgba(22, 24, 29, 0.9) 65%);
        border-bottom: 1px solid rgba(255, 193, 7, 0.18);
        padding: 1.4rem 1.75rem;
    }
    .luxury-label {
        color: #cbd5e1;
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        margin-bottom: 0.45rem;
    }
    .luxury-input {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.14) !important;
        color: #ffffff !important;
        border-radius: 0.75rem;
        padding: 0.72rem 1rem;
        font-size: 0.94rem;
        transition: all 0.2s ease;
    }
    .luxury-input:focus {
        background-color: rgba(255, 255, 255, 0.08) !important;
        border-color: #ffc107 !important;
        box-shadow: 0 0 0 0.22rem rgba(255, 193, 7, 0.2) !important;
        color: #ffffff !important;
    }
    .luxury-input::placeholder {
        color: rgba(255, 255, 255, 0.35);
    }
    .luxury-input::-webkit-calendar-picker-indicator {
        filter: invert(1) sepia(1) saturate(5) hue-rotate(10deg);
        cursor: pointer;
    }
    .luxury-readonly-box {
        background: rgba(255, 193, 7, 0.08);
        border: 1px solid rgba(255, 193, 7, 0.35);
        color: #ffc107;
        border-radius: 0.75rem;
        padding: 0.72rem 1rem;
        font-size: 0.94rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Grid Slot Jam Interaktif */
    .slot-grid-box {
        background: rgba(0, 0, 0, 0.28);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 1rem;
        padding: 1.15rem;
    }
    .hour-chip {
        border-radius: 0.65rem;
        padding: 0.55rem 0.5rem;
        font-size: 0.82rem;
        font-weight: 700;
        text-align: center;
        transition: background-color 0.18s ease, border-color 0.18s ease, color 0.18s ease, box-shadow 0.18s ease;
        user-select: none;
    }
    .hour-chip.available {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.14);
        color: #e2e8f0;
        cursor: pointer;
    }
    .hour-chip.available:hover {
        border-color: #ffc107;
        background: rgba(255, 193, 7, 0.12);
        color: #ffc107;
    }
    .hour-chip.selected {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
        border: 1px solid #ffc107 !important;
        color: #12141a !important;
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.35);
        cursor: pointer;
    }
    .hour-chip.booked {
        background: rgba(220, 53, 69, 0.14);
        border: 1px solid rgba(220, 53, 69, 0.35);
        color: #f87171;
        cursor: not-allowed;
        opacity: 0.85;
    }

    /* Kartu Ringkasan Kanan */
    .summary-sticky {
        position: sticky;
        top: 1.5rem;
    }
    .studio-banner-fallback {
        height: 190px;
        background: radial-gradient(circle at 30% 20%, rgba(255, 193, 7, 0.25) 0%, #181b22 65%, #0e1015 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid rgba(255, 193, 7, 0.18);
    }
    .summary-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.65rem 0;
        border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
        font-size: 0.9rem;
    }
    .summary-line:last-child {
        border-bottom: none;
    }
    .total-box-gold {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.16) 0%, rgba(255, 193, 7, 0.05) 100%);
        border: 1px solid rgba(255, 193, 7, 0.4);
        border-radius: 0.9rem;
        padding: 1rem 1.15rem;
    }
    .btn-gold-glow {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        border: none;
        color: #12141a;
        font-weight: 800;
        letter-spacing: 0.3px;
        padding: 0.85rem 1.75rem;
        border-radius: 0.75rem;
        box-shadow: 0 8px 25px rgba(255, 193, 7, 0.28);
        transition: all 0.25s ease;
    }
    .btn-gold-glow:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(255, 193, 7, 0.45);
        color: #090a0d;
    }
    .btn-gold-glow:disabled {
        background: rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.45);
        box-shadow: none;
        cursor: not-allowed;
    }
    .btn-promo-gold {
        background: rgba(255, 193, 7, 0.15);
        border: 1px solid rgba(255, 193, 7, 0.45);
        color: #ffc107;
        font-weight: 700;
        border-radius: 0 0.75rem 0.75rem 0;
        padding: 0 1.25rem;
        transition: all 0.2s ease;
    }
    .btn-promo-gold:hover {
        background: #ffc107;
        color: #12141a;
    }
</style>
@endpush

@section('content')
@php
    $namaStudio = $studio->nama ?? $studio->nama_studio;
    $fotoUtama = $studio->fotos->firstWhere('is_utama', true) ?? $studio->fotos->first();
    $fotoPath = $fotoUtama ? ($fotoUtama->path_foto ?? $fotoUtama->path) : null;
    $tanggalAktif = old('tanggal', $tanggal ?? request('tanggal', now()->toDateString()));

    $bookedSlotsJson = ($jadwalTerisi ?? collect())->map(function ($item) {
        return [
            'mulai' => substr($item->jam_mulai, 0, 5),
            'selesai' => substr($item->jam_selesai, 0, 5),
            'status' => $item->status,
        ];
    })->values();
@endphp

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-dark fw-semibold">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('katalog') }}" class="text-decoration-none text-dark fw-semibold">Katalog</a></li>
        <li class="breadcrumb-item"><a href="{{ route('studio.detail', $studio) }}" class="text-decoration-none text-dark fw-semibold">{{ $namaStudio }}</a></li>
        <li class="breadcrumb-item active text-muted">Reservasi Studio</li>
    </ol>
</nav>

@if(session('error'))
    <div class="alert alert-danger border-0 shadow rounded-4 d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-octagon-fill fs-5"></i>
        <div><strong>Jadwal Tidak Tersedia:</strong> {{ session('error') }}</div>
    </div>
@endif

<div class="row g-4">
    <!-- Kolom Kiri: Panel Gelap-Emas Form Reservasi -->
    <div class="col-lg-8">
        <div class="luxury-panel">
            <div class="luxury-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge rounded-pill px-3 py-1 mb-2" style="background: rgba(255,193,7,0.16); color: #ffc107; border: 1px solid rgba(255,193,7,0.35);">
                        <i class="bi bi-stars me-1"></i> RESERVASI STUDIO VIP
                    </span>
                    <h4 class="mb-0 fw-bold text-white">Atur Jadwal Latihan Anda</h4>
                </div>
                <div class="text-end">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 small" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                        <i class="bi bi-clock-history text-warning"></i>
                        <span class="text-white-50">Operasional:</span>
                        <strong class="text-white">09:00 – 22:00</strong>
                    </span>
                </div>
            </div>

            <div class="p-4 p-md-4">
                <!-- Peta Ketersediaan Jam Visual -->
                <div class="slot-grid-box mb-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <span class="small fw-bold text-white">
                            <i class="bi bi-grid-3x3-gap-fill text-warning me-2"></i>
                            Ketersediaan Jam — {{ \Carbon\Carbon::parse($tanggalAktif)->translatedFormat('d F Y') }}
                        </span>
                        <div class="d-flex align-items-center gap-3 small">
                            <span class="text-white-50"><i class="bi bi-square-fill text-warning me-1"></i> Dipilih</span>
                            <span class="text-white-50"><i class="bi bi-square-fill text-secondary opacity-50 me-1"></i> Kosong</span>
                            <span class="text-danger"><i class="bi bi-lock-fill me-1"></i> Terisi</span>
                        </div>
                    </div>

                    <div class="row g-2" id="visualSlotGrid">
                        @for($hour = 9; $hour <= 21; $hour++)
                            @php
                                $jamStr = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
                            @endphp
                            <div class="col-4 col-sm-3 col-md-2">
                                <div class="hour-chip available" data-hour="{{ $jamStr }}" onclick="pilihJamDariGrid('{{ $jamStr }}')">
                                    <div>{{ $jamStr }}</div>
                                    <div class="slot-sublabel" style="font-size: 0.68rem; font-weight: 500; opacity: 0.8;">Tersedia</div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div class="small text-white-50 mt-2" style="font-size: 0.76rem;">
                        <i class="bi bi-hand-index-thumb text-warning me-1"></i> Klik satu jam untuk 1 jam sewa, atau klik jam berikutnya yang berurutan untuk menambah lama penyewaan.
                    </div>
                </div>

                <!-- Alert Bentrok Real-time -->
                <div id="alertBentrokRealtime" class="alert border-0 rounded-3 d-none align-items-center gap-2 mb-4"
                     style="background: rgba(220, 53, 69, 0.18); border: 1px solid rgba(220, 53, 69, 0.45) !important; color: #fecaca;">
                    <i class="bi bi-shield-exclamation fs-5 text-danger"></i>
                    <div class="small" id="teksBentrokRealtime"></div>
                </div>

                <form action="{{ route('booking.store', $studio) }}" method="POST" id="formBooking">
                    @csrf
                    <input type="hidden" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai', '09:00') }}">
                    <input type="hidden" name="durasi_jam" id="durasi_jam" value="{{ old('durasi_jam', 1) }}">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="tanggal" class="luxury-label">Tanggal Main</label>
                            <input type="date"
                                   name="tanggal"
                                   id="tanggal"
                                   class="form-control luxury-input @error('tanggal') is-invalid @enderror"
                                   value="{{ $tanggalAktif }}"
                                   min="{{ now()->toDateString() }}"
                                   required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="luxury-label">Jam Mulai (Otomatis)</label>
                            <div class="luxury-readonly-box">
                                <span id="displayJamMulai">09:00</span>
                                <i class="bi bi-clock text-warning"></i>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="luxury-label">Durasi Sewa (Otomatis)</label>
                            <div class="luxury-readonly-box">
                                <span id="displayDurasiOtomatis">1 Jam (09:00 – 10:00)</span>
                                <i class="bi bi-hourglass-split text-warning"></i>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <label for="kode_promo" class="luxury-label">
                                <i class="bi bi-ticket-perforated-fill text-warning me-1"></i>Kode Voucher / Promo (Opsional)
                            </label>
                            <div class="input-group">
                                <input type="text"
                                       name="kode_promo"
                                       id="kode_promo"
                                       class="form-control luxury-input text-uppercase"
                                       style="border-radius: 0.75rem 0 0 0.75rem;"
                                       placeholder="Masukkan kode promo (Contoh: SOBATPOSITIF)"
                                       value="{{ old('kode_promo') }}">
                                <button type="button" id="btnCekPromo" class="btn btn-promo-gold">
                                    Gunakan Promo
                                </button>
                            </div>
                            <div id="promoFeedback" class="small mt-2"></div>
                        </div>

                        <div class="col-12 mt-3">
                            <label for="catatan" class="luxury-label">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan"
                                      id="catatan"
                                      rows="3"
                                      class="form-control luxury-input @error('catatan') is-invalid @enderror"
                                      placeholder="Contoh: Request tambahan mic vocal, stand keyboard, atau setting amplifier...">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <a href="{{ route('studio.detail', $studio) }}" class="btn btn-outline-light rounded-3 px-4 py-2" style="border-color: rgba(255,255,255,0.2);">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" id="btnSubmitBooking" class="btn btn-gold-glow">
                            <i class="bi bi-check2-circle me-1"></i> Konfirmasi Reservasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Kartu Ringkasan Biaya Dark-Gold -->
    <div class="col-lg-4">
        <div class="luxury-panel summary-sticky">
            <div class="position-relative">
                @if($fotoPath)
                    <img src="{{ asset('storage/' . $fotoPath) }}"
                         class="w-100" style="height: 190px; object-fit: cover;" alt="{{ $namaStudio }}">
                @else
                    <div class="studio-banner-fallback px-3 text-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                             style="width: 54px; height: 54px; background: rgba(255,193,7,0.15); border: 1px solid rgba(255,193,7,0.4);">
                            <i class="bi bi-music-note-beamed text-warning fs-4"></i>
                        </div>
                        <div class="fw-bold text-warning">{{ $namaStudio }}</div>
                        <small class="text-white-50">Studio Musik Positif</small>
                    </div>
                @endif

                <span class="badge bg-dark bg-opacity-75 text-warning position-absolute bottom-0 start-0 m-3 px-3 py-2 rounded-pill border border-warning border-opacity-25">
                    <i class="bi bi-people-fill me-1"></i> Kapasitas {{ $studio->kapasitas }} Orang
                </span>
            </div>

            <div class="p-4">
                <h5 class="fw-bold text-white mb-1">{{ $namaStudio }}</h5>
                <p class="text-white-50 small mb-3">Ringkasan rincian biaya sesi latihan</p>

                <div class="mb-3">
                    <div class="summary-line">
                        <span class="text-white-50">Tanggal</span>
                        <span class="fw-semibold text-white" id="labelTanggal">{{ \Carbon\Carbon::parse($tanggalAktif)->format('d M Y') }}</span>
                    </div>
                    <div class="summary-line">
                        <span class="text-white-50">Waktu Sesi</span>
                        <span class="fw-bold text-warning" id="labelWaktu">09:00 - 10:00</span>
                    </div>
                    <div class="summary-line">
                        <span class="text-white-50">Tarif per Jam</span>
                        <span class="fw-semibold text-white">Rp {{ number_format($studio->harga_per_jam, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-line">
                        <span class="text-white-50">Durasi Sewa</span>
                        <span class="fw-semibold text-white" id="labelDurasi">1 Jam</span>
                    </div>
                    <div class="summary-line">
                        <span class="text-white-50">Subtotal</span>
                        <span class="fw-semibold text-white" id="labelSubtotal">Rp {{ number_format($studio->harga_per_jam, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-line">
                        <span class="text-white-50">Diskon Promo</span>
                        <span class="fw-bold text-success" id="labelDiskon">- Rp 0</span>
                    </div>
                </div>

                <div class="total-box-gold mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-white-50">Total Pembayaran</div>
                        <div class="fw-bold text-white" style="font-size: 0.78rem;">Sudah termasuk fasilitas studio</div>
                    </div>
                    <span class="fs-4 fw-bold text-warning" id="labelTotal">Rp {{ number_format($studio->harga_per_jam, 0, ',', '.') }}</span>
                </div>

                <div class="small text-white-50 d-flex align-items-start gap-2 p-2 rounded-3" style="background: rgba(255,255,255,0.03);">
                    <i class="bi bi-shield-check text-warning fs-5"></i>
                    <span>Selesaikan pembayaran dalam <strong>5 menit</strong> setelah konfirmasi agar jadwal tidak terlepas otomatis.</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const hargaPerJam = {{ (int) $studio->harga_per_jam }};
    const bookedSlots = @json($bookedSlotsJson);
    const jamTutupMenit = 22 * 60;
    let diskonAktif = 0;

    function formatRupiah(angka) {
        return 'Rp ' + Number(angka).toLocaleString('id-ID');
    }

    function timeToMinutes(timeStr) {
        const [h, m] = timeStr.split(':').map(Number);
        return (h * 60) + (m || 0);
    }

    function minutesToTime(totalMenit) {
        const h = Math.floor(totalMenit / 60) % 24;
        const m = totalMenit % 60;
        return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
    }

    function isHourBooked(hourStr) {
        const startMin = timeToMinutes(hourStr);
        const endMin = startMin + 60;
        for (const slot of bookedSlots) {
            const bookedStart = timeToMinutes(slot.mulai);
            const bookedEnd = timeToMinutes(slot.selesai);
            if (startMin < bookedEnd && endMin > bookedStart) {
                return slot;
            }
        }
        return null;
    }

    function getMaxDurasiTersedia(jamMulaiStr) {
        const startMin = timeToMinutes(jamMulaiStr);
        let maxJam = 0;

        for (let i = 0; i < 12; i++) {
            const cekStart = startMin + (i * 60);
            const cekEnd = cekStart + 60;
            if (cekEnd > jamTutupMenit) break;

            let tabrakan = false;
            for (const slot of bookedSlots) {
                const bookedStart = timeToMinutes(slot.mulai);
                const bookedEnd = timeToMinutes(slot.selesai);
                if (cekStart < bookedEnd && cekEnd > bookedStart) {
                    tabrakan = true;
                    break;
                }
            }
            if (tabrakan) break;
            maxJam++;
        }
        return maxJam;
    }

    function renderGridSlots() {
        const jamMulaiVal = document.getElementById('jam_mulai').value || '09:00';
        const durasi = parseInt(document.getElementById('durasi_jam').value) || 1;
        const selStart = timeToMinutes(jamMulaiVal);
        const selEnd = selStart + (durasi * 60);

        document.querySelectorAll('.hour-chip').forEach(chip => {
            const hourStr = chip.dataset.hour;
            const hStart = timeToMinutes(hourStr);
            const hEnd = hStart + 60;
            const sublabel = chip.querySelector('.slot-sublabel');
            const bookedInfo = isHourBooked(hourStr);

            chip.classList.remove('booked', 'selected', 'available');

            if (bookedInfo) {
                chip.classList.add('booked');
                sublabel.innerText = 'Terisi';
            } else if (hStart >= selStart && hEnd <= selEnd) {
                chip.classList.add('selected');
                sublabel.innerText = 'Dipilih';
            } else {
                chip.classList.add('available');
                sublabel.innerText = 'Tersedia';
            }
        });
    }

    function pilihJamDariGrid(hourStr) {
        if (isHourBooked(hourStr)) return;

        const inputJam = document.getElementById('jam_mulai');
        const inputDurasi = document.getElementById('durasi_jam');
        const currentStart = timeToMinutes(inputJam.value || '09:00');
        const currentDurasi = parseInt(inputDurasi.value) || 1;
        const currentEnd = currentStart + (currentDurasi * 60);
        const clickedMin = timeToMinutes(hourStr);

        // Jika klik jam yang sudah terpilih: reset jadi 1 jam di titik klik tersebut
        if (clickedMin >= currentStart && clickedMin < currentEnd) {
            inputJam.value = hourStr;
            inputDurasi.value = 1;
            hitungTotal();
            return;
        }

        // Jika klik jam setelah jam mulai saat ini dan seluruh rentang di antaranya kosong, otomatis tambah durasinya
        if (clickedMin > currentStart) {
            const selisihJam = Math.round((clickedMin - currentStart) / 60) + 1;
            const maxBisa = getMaxDurasiTersedia(inputJam.value);
            if (selisihJam <= maxBisa) {
                inputDurasi.value = selisihJam;
                hitungTotal();
                return;
            }
        }

        // Klik jam baru di luar rentang: mulai dari jam tersebut dengan durasi 1 jam
        inputJam.value = hourStr;
        inputDurasi.value = 1;
        hitungTotal();
    }

    function hitungTotal() {
        const jamMulaiVal = document.getElementById('jam_mulai').value || '09:00';
        const durasi = parseInt(document.getElementById('durasi_jam').value) || 1;
        const startMin = timeToMinutes(jamMulaiVal);
        const endMin = startMin + (durasi * 60);
        const jamSelesaiStr = minutesToTime(endMin);

        // Perbarui tampilan otomatis pada kotak Jam Mulai & Durasi Sewa
        document.getElementById('displayJamMulai').innerText = jamMulaiVal;
        document.getElementById('displayDurasiOtomatis').innerText = `${durasi} Jam (${jamMulaiVal} – ${jamSelesaiStr})`;

        // Perbarui ringkasan biaya kanan
        const subtotal = hargaPerJam * durasi;
        const diskonFinal = Math.min(diskonAktif, subtotal);
        const total = subtotal - diskonFinal;

        document.getElementById('labelWaktu').innerText = `${jamMulaiVal} - ${jamSelesaiStr}`;
        document.getElementById('labelDurasi').innerText = `${durasi} Jam`;
        document.getElementById('labelSubtotal').innerText = formatRupiah(subtotal);
        document.getElementById('labelDiskon').innerText = '- ' + formatRupiah(diskonFinal);
        document.getElementById('labelTotal').innerText = formatRupiah(total);

        renderGridSlots();

        // Kunci tombol jika tidak ada jam kosong sama sekali
        const btnSubmit = document.getElementById('btnSubmitBooking');
        if (isHourBooked(jamMulaiVal)) {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="bi bi-lock-fill me-1"></i> Jadwal Sudah Terisi';
        } else {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Konfirmasi Reservasi';
        }
    }

    document.getElementById('tanggal').addEventListener('change', function () {
        if (this.value) {
            window.location.href = "{{ route('booking.create', $studio) }}?tanggal=" + encodeURIComponent(this.value);
        }
    });

    document.getElementById('btnCekPromo').addEventListener('click', function () {
        const kode = document.getElementById('kode_promo').value.trim();
        const durasi = parseInt(document.getElementById('durasi_jam').value) || 1;
        const subtotal = hargaPerJam * durasi;
        const feedback = document.getElementById('promoFeedback');

        if (!kode) {
            feedback.innerHTML = '<span class="text-warning"><i class="bi bi-exclamation-circle me-1"></i>Masukkan kode promo terlebih dahulu.</span>';
            return;
        }

        fetch("{{ route('promo.cek') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ kode: kode, subtotal: subtotal })
        })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            if (ok && data.valid) {
                diskonAktif = data.diskon;
                feedback.innerHTML = '<span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>' + data.message + '</span>';
            } else {
                diskonAktif = 0;
                feedback.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i>' + (data.message || 'Kode promo tidak valid.') + '</span>';
            }
            hitungTotal();
        })
        .catch(() => {
            feedback.innerHTML = '<span class="text-danger">Terjadi kesalahan saat memeriksa promo.</span>';
        });
    });

    // Otomatis pilih jam pertama yang tersedia saat halaman dibuka
    if (isHourBooked(document.getElementById('jam_mulai').value)) {
        const firstFree = Array.from(document.querySelectorAll('.hour-chip')).find(c => !isHourBooked(c.dataset.hour));
        if (firstFree) {
            document.getElementById('jam_mulai').value = firstFree.dataset.hour;
        }
    }
    document.getElementById('durasi_jam').value = 1;
    hitungTotal();
</script>
@endpush