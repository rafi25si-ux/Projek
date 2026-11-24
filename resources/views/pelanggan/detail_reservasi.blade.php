@extends('layouts.app')

@section('title', 'Detail Reservasi ' . ($reservasi->kode_booking ?? $reservasi->kode))

@push('styles')
<style>
    .luxury-card-dark {
        background: linear-gradient(160deg, #1a1d24 0%, #12141a 55%, #0d0f13 100%);
        border: 1px solid rgba(255, 193, 7, 0.22);
        border-radius: 1.25rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.06);
        color: #f8f9fa;
        overflow: hidden;
    }
    .luxury-card-header {
        background: radial-gradient(circle at top right, rgba(255, 193, 7, 0.16) 0%, rgba(22, 24, 29, 0.9) 65%);
        border-bottom: 1px solid rgba(255, 193, 7, 0.18);
        padding: 1.25rem 1.5rem;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.7rem 0;
        border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
        font-size: 0.92rem;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .rek-box-gold {
        background: rgba(255, 193, 7, 0.07);
        border: 1px solid rgba(255, 193, 7, 0.32);
        border-radius: 1rem;
        padding: 1.15rem;
    }
    .rek-number-pill {
        background: rgba(0, 0, 0, 0.45);
        border: 1px solid rgba(255, 193, 7, 0.35);
        border-radius: 0.75rem;
        padding: 0.65rem 1rem;
    }
    .luxury-file-input {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.16) !important;
        color: #ffffff !important;
        border-radius: 0.75rem;
        padding: 0.65rem 0.9rem;
    }
    .luxury-file-input::file-selector-button {
        background: rgba(255, 193, 7, 0.18);
        color: #ffc107;
        border: none;
        border-radius: 0.5rem;
        padding: 0.35rem 0.85rem;
        margin-right: 0.85rem;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-gold-luxury {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        border: none;
        color: #12141a;
        font-weight: 800;
        padding: 0.78rem 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 8px 24px rgba(255, 193, 7, 0.25);
        transition: all 0.2s ease;
    }
    .btn-gold-luxury:hover {
        box-shadow: 0 10px 28px rgba(255, 193, 7, 0.42);
        color: #090a0d;
    }
</style>
@endpush

@section('content')
@php
    $kodeBooking = $reservasi->kode_booking ?? $reservasi->kode;
    $namaStudio = $reservasi->studio->nama ?? $reservasi->studio->nama_studio ?? '-';
    $totalBayar = $reservasi->total_bayar ?: $reservasi->total_harga;
    $batasBayarTimestamp = $reservasi->created_at->copy()->addMinutes(5)->timestamp * 1000;
    $badgeClass = match($reservasi->status) {
        'Menunggu pembayaran' => 'bg-warning text-dark',
        'Menunggu verifikasi' => 'bg-info text-dark',
        'Dikonfirmasi' => 'bg-primary',
        'Selesai' => 'bg-success',
        'Dibatalkan' => 'bg-danger',
        default => 'bg-secondary'
    };
@endphp

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <a href="{{ route('riwayat') }}" class="text-decoration-none text-dark fw-semibold small">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
        </a>
        <h3 class="fw-bold mt-1 mb-0">Detail Reservasi <span class="text-warning">#{{ $kodeBooking }}</span></h3>
    </div>
    <div class="d-flex gap-2">
        @if(in_array($reservasi->status, ['Dikonfirmasi', 'Selesai']))
            <a href="{{ route('riwayat.invoice', $reservasi) }}" target="_blank" class="btn btn-gold-luxury">
                <i class="bi bi-printer-fill me-1"></i> Cetak Invoice
            </a>
        @endif
        @if(in_array($reservasi->status, ['Menunggu pembayaran', 'Menunggu verifikasi']))
            <form action="{{ route('riwayat.batal', $reservasi) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan reservasi ini?')">
                @csrf
                <button type="submit" class="btn btn-outline-danger rounded-3 px-3">Batalkan Pesanan</button>
            </form>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- Kiri: Info Reservasi & Ulasan -->
    <div class="col-lg-7">
        <div class="luxury-card-dark mb-4">
            <div class="luxury-card-header d-flex justify-content-between align-items-center">
                <span class="text-white-50 fw-semibold">Status Pesanan</span>
                <div class="d-flex align-items-center gap-2">
                    @if($reservasi->status === 'Menunggu verifikasi')
                        <span class="small text-info d-inline-flex align-items-center gap-1" title="Halaman otomatis diperbarui saat Admin mengonfirmasi">
                            <span class="spinner-grow spinner-grow-sm" role="status"></span> Auto-cek aktif
                        </span>
                    @endif
                    <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fs-6">{{ $reservasi->status }}</span>
                </div>
            </div>

            <div class="p-4">
                <div class="mb-4">
                    <div class="detail-row">
                        <span class="text-white-50">Studio</span>
                        <span class="fw-bold text-white">{{ $namaStudio }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="text-white-50">Tanggal Main</span>
                        <span class="fw-semibold text-white">{{ \Carbon\Carbon::parse($reservasi->tanggal)->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="text-white-50">Waktu & Durasi</span>
                        <span class="fw-bold text-warning">{{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }} ({{ $reservasi->durasi_jam }} Jam)</span>
                    </div>
                    <div class="detail-row">
                        <span class="text-white-50">Catatan</span>
                        <span class="text-white">{{ $reservasi->catatan ?: '-' }}</span>
                    </div>
                </div>

                <h6 class="fw-bold text-warning mb-3"><i class="bi bi-receipt me-2"></i>Rincian Pembayaran</h6>
                <div class="detail-row">
                    <span class="text-white-50">Subtotal ({{ $reservasi->durasi_jam }} jam)</span>
                    <span class="text-white">Rp {{ number_format($reservasi->subtotal ?: $totalBayar, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span class="text-white-50">Diskon {{ $reservasi->promo ? '(' . $reservasi->promo->kode . ')' : '' }}</span>
                    <span class="text-success fw-semibold">- Rp {{ number_format($reservasi->diskon ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="mt-3 p-3 rounded-3 d-flex justify-content-between align-items-center"
                     style="background: rgba(255, 193, 7, 0.12); border: 1px solid rgba(255, 193, 7, 0.35);">
                    <span class="fw-bold text-white">Total Bayar</span>
                    <span class="fs-4 fw-bold text-warning">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Form Ulasan jika status Selesai -->
        @if($reservasi->status === 'Selesai')
            <div class="luxury-card-dark">
                <div class="luxury-card-header">
                    <h5 class="fw-bold mb-0 text-white"><i class="bi bi-star-fill text-warning me-2"></i>Ulasan Pengalaman Anda</h5>
                </div>
                <div class="p-4">
                    @if($reservasi->ulasan)
                        <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            <div class="text-warning mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $reservasi->ulasan->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                            <p class="mb-0 text-white">{{ $reservasi->ulasan->komentar }}</p>
                        </div>
                    @else
                        <form action="{{ route('ulasan.store', $reservasi) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-white-50 small">Rating Studio</label>
                                <select name="rating" class="form-select bg-dark text-white border-secondary" required>
                                    <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Puas)</option>
                                    <option value="4">⭐⭐⭐⭐ (4 - Puas)</option>
                                    <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                    <option value="2">⭐⭐ (2 - Kurang)</option>
                                    <option value="1">⭐ (1 - Buruk)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-white-50 small">Komentar</label>
                                <textarea name="komentar" rows="3" class="form-control bg-dark text-white border-secondary" required placeholder="Bagaimana kualitas suara, peralatan, dan pelayanan studio?"></textarea>
                            </div>
                            <button type="submit" class="btn btn-gold-luxury">Kirim Ulasan</button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Kanan: Instruksi & Upload Bukti Pembayaran -->
    <div class="col-lg-5">
        <div class="luxury-card-dark">
            <div class="luxury-card-header">
                <h6 class="mb-0 fw-bold text-white"><i class="bi bi-bank text-warning me-2"></i>Informasi & Bukti Pembayaran</h6>
            </div>
            <div class="p-4">
                <!-- Banner Hitung Mundur 5 Menit -->
                @if($reservasi->status === 'Menunggu pembayaran')
                    <div class="alert border-0 d-flex justify-content-between align-items-center py-2 px-3 mb-3 rounded-3"
                         style="background: rgba(220, 53, 69, 0.2); border: 1px solid rgba(220, 53, 69, 0.45) !important; color: #fecaca;">
                        <span class="small fw-semibold"><i class="bi bi-alarm-fill text-danger me-1"></i> Batas Waktu Bayar:</span>
                        <span class="badge bg-danger fs-6 font-monospace" id="countdownTimer">05:00</span>
                    </div>
                @endif

                <!-- Info Rekening Transfer Bank Mandiri -->
                <div class="rek-box-gold mb-3">
                    <div class="small text-white-50 mb-1">Metode Pembayaran</div>
                    <div class="fw-bold text-white fs-6 mb-3"><i class="bi bi-credit-card-2-front text-warning me-1"></i> Transfer Bank Mandiri</div>

                    <div class="small text-white-50 mb-1">Nomor Rekening</div>
                    <div class="d-flex justify-content-between align-items-center rek-number-pill my-1">
                        <span class="fs-5 fw-bold text-warning font-monospace" id="noRekMandiri">1080031980353</span>
                        <button type="button" class="btn btn-sm btn-outline-warning rounded-3" onclick="salinRekening(this)">
                            <i class="bi bi-clipboard me-1"></i> Salin
                        </button>
                    </div>
                    <div class="small text-white-50 mt-2">a.n. <strong class="text-white">Studio Musik Positif</strong></div>

                    <hr style="border-color: rgba(255,255,255,0.12);" class="my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-white-50">Total Transfer:</span>
                        <strong class="fs-5 text-warning">Rp {{ number_format($totalBayar, 0, ',', '.') }}</strong>
                    </div>
                </div>

                @if($reservasi->pembayaran)
                    <div class="mb-3">
                        <span class="text-white-50 small d-block">Metode Pembayaran:</span>
                        <strong class="text-white">{{ $reservasi->pembayaran->metode_pembayaran }}</strong>
                    </div>
                    <div class="mb-3">
                        <span class="text-white-50 small d-block">Status Verifikasi:</span>
                        <span class="badge {{ $reservasi->pembayaran->status === 'Disetujui' ? 'bg-success' : ($reservasi->pembayaran->status === 'Ditolak' ? 'bg-danger' : 'bg-info text-dark') }} mt-1">
                            {{ $reservasi->pembayaran->status }}
                        </span>
                    </div>
                    @if($reservasi->pembayaran->alasan_penolakan)
                        <div class="alert alert-danger py-2 small mb-3">
                            <strong>Alasan Ditolak:</strong> {{ $reservasi->pembayaran->alasan_penolakan }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <span class="text-white-50 small d-block mb-2">Bukti Transfer Terunggah:</span>
                        <a href="{{ asset('storage/' . $reservasi->pembayaran->bukti_path) }}" target="_blank">
                            <img src="{{ asset('storage/' . $reservasi->pembayaran->bukti_path) }}" class="img-fluid rounded-3 border border-warning border-opacity-25" alt="Bukti Pembayaran">
                        </a>
                    </div>
                @endif

                @if($reservasi->status === 'Menunggu pembayaran' || ($reservasi->pembayaran && $reservasi->pembayaran->status === 'Ditolak'))
                    <form action="{{ route('riwayat.upload_bukti', $reservasi) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="metode_pembayaran" value="Transfer Bank Mandiri">

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-white-50 small">Upload Bukti Transfer (JPG/PNG, Max 2MB)</label>
                            <input type="file" name="bukti" class="form-control luxury-file-input @error('bukti') is-invalid @enderror" accept="image/*" required>
                            @error('bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-gold-luxury w-100">
                            <i class="bi bi-upload me-1"></i> Kirim Bukti Pembayaran
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function salinRekening(btn) {
        const norek = document.getElementById('noRekMandiri').innerText;
        navigator.clipboard.writeText(norek).then(() => {
            btn.innerHTML = '<i class="bi bi-check2 me-1"></i> Tersalin';
            btn.classList.replace('btn-outline-warning', 'btn-success');
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-clipboard me-1"></i> Salin';
                btn.classList.replace('btn-success', 'btn-outline-warning');
            }, 2000);
        });
    }

    @if($reservasi->status === 'Menunggu pembayaran')
    document.addEventListener('DOMContentLoaded', function () {
        const deadline = {{ $batasBayarTimestamp }};
        const timerEl = document.getElementById('countdownTimer');

        function updateTimer() {
            const now = new Date().getTime();
            const distance = deadline - now;

            if (distance <= 0) {
                timerEl.textContent = '00:00';
                window.location.reload();
                return;
            }

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            timerEl.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    });
    @endif

    @if($reservasi->status === 'Menunggu verifikasi')
    (function () {
        const statusAwal = @json($reservasi->status);
        const urlCekStatus = "{{ route('riwayat.cek-status', $reservasi) }}";

        const intervalCek = setInterval(() => {
            fetch(urlCekStatus, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.ok ? res.json() : null)
            .then(data => {
                if (data && data.status && data.status !== statusAwal) {
                    clearInterval(intervalCek);
                    window.location.reload();
                }
            })
            .catch(() => {});
        }, 5000);
    })();
    @endif
</script>
@endsection