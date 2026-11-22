@extends('layouts.app')

@section('title', 'Booking ' . ($studio->nama ?? $studio->nama_studio) . ' - Studio Musik Positif')

@section('content')
@php
    $namaStudio = $studio->nama ?? $studio->nama_studio;
    $fotoUtama = $studio->fotos->firstWhere('is_utama', true) ?? $studio->fotos->first();
@endphp

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('studio.detail', $studio) }}" class="text-decoration-none">{{ $namaStudio }}</a></li>
        <li class="breadcrumb-item active">Form Booking</li>
    </ol>
</nav>

<div class="row g-4">
    <!-- Form Pemesanan -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-plus text-warning me-2"></i>Form Reservasi Studio</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('booking.store', $studio) }}" method="POST" id="formBooking">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal Main</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal', request('tanggal', now()->toDateString())) }}" min="{{ now()->toDateString() }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror"
                                   value="{{ old('jam_mulai', '10:00') }}" required>
                            @error('jam_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Durasi (Jam)</label>
                            <select name="durasi_jam" id="durasi_jam" class="form-select @error('durasi_jam') is-invalid @enderror" required>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('durasi_jam', 2) == $i ? 'selected' : '' }}>{{ $i }} Jam</option>
                                @endfor
                            </select>
                            @error('durasi_jam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Kode Promo (Opsional)</label>
                            <div class="input-group">
                                <input type="text" name="kode_promo" id="kode_promo" class="form-control text-uppercase"
                                       placeholder="Masukkan kode promo jika ada" value="{{ old('kode_promo') }}">
                                <button type="button" id="btnCekPromo" class="btn btn-outline-dark">Gunakan Promo</button>
                            </div>
                            <div id="promoFeedback" class="small mt-1"></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror"
                                      placeholder="Contoh: Request tambahan mic vocal atau stand keyboard...">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('studio.detail', $studio) }}" class="btn btn-outline-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning text-dark fw-bold px-4">
                            <i class="bi bi-check2-circle me-1"></i> Konfirmasi Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Ringkasan Biaya -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <img src="{{ $fotoUtama ? asset('storage/' . $fotoUtama->path_foto) : 'https://placehold.co/600x350/212529/ffc107?text=' . urlencode($namaStudio) }}"
                 class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $namaStudio }}">
            <div class="card-body">
                <h5 class="fw-bold mb-1">{{ $namaStudio }}</h5>
                <p class="text-muted small mb-3"><i class="bi bi-people me-1"></i> Kapasitas {{ $studio->kapasitas }} Orang</p>

                <ul class="list-group list-group-flush small mb-3">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Harga per Jam</span>
                        <strong>Rp {{ number_format($studio->harga_per_jam, 0, ',', '.') }}</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Durasi Sewa</span>
                        <strong id="labelDurasi">2 Jam</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Subtotal</span>
                        <strong id="labelSubtotal">Rp {{ number_format($studio->harga_per_jam * 2, 0, ',', '.') }}</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between text-success">
                        <span>Diskon Promo</span>
                        <strong id="labelDiskon">- Rp 0</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between fs-6 fw-bold text-primary">
                        <span>Total Bayar</span>
                        <span id="labelTotal">Rp {{ number_format($studio->harga_per_jam * 2, 0, ',', '.') }}</span>
                    </li>
                </ul>

                <div class="alert alert-info small mb-0">
                    <i class="bi bi-info-circle me-1"></i> Pembayaran dilakukan via transfer setelah booking dibuat.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const hargaPerJam = {{ (int) $studio->harga_per_jam }};
    let diskonAktif = 0;

    function formatRupiah(angka) {
        return 'Rp ' + Number(angka).toLocaleString('id-ID');
    }

    function hitungTotal() {
        const durasi = parseInt(document.getElementById('durasi_jam').value) || 1;
        const subtotal = hargaPerJam * durasi;
        const diskonFinal = Math.min(diskonAktif, subtotal);
        const total = subtotal - diskonFinal;

        document.getElementById('labelDurasi').innerText = durasi + ' Jam';
        document.getElementById('labelSubtotal').innerText = formatRupiah(subtotal);
        document.getElementById('labelDiskon').innerText = '- ' + formatRupiah(diskonFinal);
        document.getElementById('labelTotal').innerText = formatRupiah(total);
    }

    document.getElementById('durasi_jam').addEventListener('change', hitungTotal);

    document.getElementById('btnCekPromo').addEventListener('click', function () {
        const kode = document.getElementById('kode_promo').value.trim();
        const durasi = parseInt(document.getElementById('durasi_jam').value) || 1;
        const subtotal = hargaPerJam * durasi;
        const feedback = document.getElementById('promoFeedback');

        if (!kode) {
            feedback.innerHTML = '<span class="text-danger">Masukkan kode promo terlebih dahulu.</span>';
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
                feedback.innerHTML = '<span class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>' + data.message + '</span>';
            } else {
                diskonAktif = 0;
                feedback.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>' + (data.message || 'Kode promo tidak valid.') + '</span>';
            }
            hitungTotal();
        })
        .catch(() => {
            feedback.innerHTML = '<span class="text-danger">Terjadi kesalahan saat memeriksa promo.</span>';
        });
    });

    hitungTotal();
</script>
@endpush