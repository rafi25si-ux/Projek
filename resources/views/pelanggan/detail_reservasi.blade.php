@extends('layouts.app')

@section('title', 'Detail Reservasi ' . ($reservasi->kode_booking ?? $reservasi->kode))

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

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('riwayat') }}" class="text-decoration-none small"><i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat</a>
        <h3 class="fw-bold mt-1 mb-0">Detail Reservasi #{{ $kodeBooking }}</h3>
    </div>
    <div class="d-flex gap-2">
        @if(in_array($reservasi->status, ['Dikonfirmasi', 'Selesai']))
            <a href="{{ route('riwayat.invoice', $reservasi) }}" target="_blank" class="btn btn-primary">
                <i class="bi bi-printer me-1"></i> Cetak Invoice
            </a>
        @endif
        @if(in_array($reservasi->status, ['Menunggu pembayaran', 'Menunggu verifikasi']))
            <form action="{{ route('riwayat.batal', $reservasi) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan reservasi ini?')">
                @csrf
                <button type="submit" class="btn btn-outline-danger">Batalkan Pesanan</button>
            </form>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- Kiri: Info Reservasi & Ulasan -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Status Pesanan</span>
                    <span class="badge {{ $badgeClass }} fs-6">{{ $reservasi->status }}</span>
                </div>
                <hr>
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted ps-0" width="35%">Studio</td>
                        <td class="fw-bold">{{ $namaStudio }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Tanggal Main</td>
                        <td class="fw-semibold">{{ \Carbon\Carbon::parse($reservasi->tanggal)->translatedFormat('l, d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Waktu & Durasi</td>
                        <td>{{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }} ({{ $reservasi->durasi_jam }} Jam)</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Catatan</td>
                        <td>{{ $reservasi->catatan ?: '-' }}</td>
                    </tr>
                </table>

                <hr>
                <h6 class="fw-bold mb-3">Rincian Pembayaran</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal ({{ $reservasi->durasi_jam }} jam)</span>
                    <span>Rp {{ number_format($reservasi->subtotal ?: $totalBayar, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2 text-success">
                    <span>Diskon {{ $reservasi->promo ? '(' . $reservasi->promo->kode . ')' : '' }}</span>
                    <span>- Rp {{ number_format($reservasi->diskon ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2 text-primary">
                    <span>Total Bayar</span>
                    <span>Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Form Ulasan jika status Selesai -->
        @if($reservasi->status === 'Selesai')
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-star-fill text-warning me-2"></i>Ulasan Pengalaman Anda</h5>
                    @if($reservasi->ulasan)
                        <div class="p-3 bg-light rounded">
                            <div class="text-warning mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $reservasi->ulasan->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                            <p class="mb-0">{{ $reservasi->ulasan->komentar }}</p>
                        </div>
                    @else
                        <form action="{{ route('ulasan.store', $reservasi) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rating Studio</label>
                                <select name="rating" class="form-select" required>
                                    <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Puas)</option>
                                    <option value="4">⭐⭐⭐⭐ (4 - Puas)</option>
                                    <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                    <option value="2">⭐⭐ (2 - Kurang)</option>
                                    <option value="1">⭐ (1 - Buruk)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Komentar</label>
                                <textarea name="komentar" rows="3" class="form-control" required placeholder="Bagaimana kualitas suara, peralatan, dan pelayanan studio?"></textarea>
                            </div>
                            <button type="submit" class="btn btn-dark">Kirim Ulasan</button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Kanan: Instruksi & Upload Bukti Pembayaran -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bank text-warning me-2"></i>Informasi & Bukti Pembayaran</h6>
            </div>
            <div class="card-body p-4">
                <!-- Banner Hitung Mundur 5 Menit -->
                @if($reservasi->status === 'Menunggu pembayaran')
                    <div class="alert alert-danger d-flex justify-content-between align-items-center py-2 px-3 mb-3">
                        <span class="small fw-semibold"><i class="bi bi-alarm me-1"></i> Batas Waktu Bayar:</span>
                        <span class="badge bg-danger fs-6 font-monospace" id="countdownTimer">05:00</span>
                    </div>
                @endif

                <!-- Info Rekening Transfer Bank Mandiri -->
                <div class="p-3 mb-3 border border-warning rounded bg-light">
                    <div class="small text-muted mb-1">Metode Pembayaran</div>
                    <div class="fw-bold text-dark fs-6 mb-2"><i class="bi bi-credit-card-2-front text-primary me-1"></i> Transfer Bank Mandiri</div>
                    
                    <div class="small text-muted">Nomor Rekening</div>
                    <div class="d-flex justify-content-between align-items-center bg-white px-3 py-2 rounded border my-1">
                        <span class="fs-5 fw-bold text-primary" id="noRekMandiri">1080031980353</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="salinRekening(this)">
                            <i class="bi bi-clipboard me-1"></i> Salin
                        </button>
                    </div>
                    <div class="small text-muted mt-1">a.n. <strong>Studio Musik Positif</strong></div>

                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-muted">Total Transfer:</span>
                        <strong class="fs-5 text-dark">Rp {{ number_format($totalBayar, 0, ',', '.') }}</strong>
                    </div>
                </div>

                @if($reservasi->pembayaran)
                    <div class="mb-3">
                        <span class="text-muted small d-block">Metode Pembayaran:</span>
                        <strong>{{ $reservasi->pembayaran->metode_pembayaran }}</strong>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small d-block">Status Verifikasi:</span>
                        <span class="badge {{ $reservasi->pembayaran->status === 'Disetujui' ? 'bg-success' : ($reservasi->pembayaran->status === 'Ditolak' ? 'bg-danger' : 'bg-info text-dark') }}">
                            {{ $reservasi->pembayaran->status }}
                        </span>
                    </div>
                    @if($reservasi->pembayaran->alasan_penolakan)
                        <div class="alert alert-danger py-2 small mb-3">
                            <strong>Alasan Ditolak:</strong> {{ $reservasi->pembayaran->alasan_penolakan }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Bukti Transfer Terunggah:</span>
                        <a href="{{ asset('storage/' . $reservasi->pembayaran->bukti_path) }}" target="_blank">
                            <img src="{{ asset('storage/' . $reservasi->pembayaran->bukti_path) }}" class="img-fluid rounded border" alt="Bukti Pembayaran">
                        </a>
                    </div>
                @endif

                @if($reservasi->status === 'Menunggu pembayaran' || ($reservasi->pembayaran && $reservasi->pembayaran->status === 'Ditolak'))
                    <form action="{{ route('riwayat.upload_bukti', $reservasi) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="metode_pembayaran" value="Transfer Bank Mandiri">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload Bukti Transfer (JPG/PNG, Max 2MB)</label>
                            <input type="file" name="bukti" class="form-control @error('bukti') is-invalid @enderror" accept="image/*" required>
                            @error('bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-warning text-dark fw-bold w-100">
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
            btn.classList.replace('btn-outline-secondary', 'btn-success');
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-clipboard me-1"></i> Salin';
                btn.classList.replace('btn-success', 'btn-outline-secondary');
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
</script>
@endsection