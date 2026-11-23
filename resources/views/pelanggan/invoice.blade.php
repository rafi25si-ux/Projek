<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $reservasi->kode_booking ?? $reservasi->kode }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #212529;
        }
        .invoice-box {
            max-width: 800px;
            margin: 30px auto;
            padding: 40px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-top: 6px solid #ffc107;
        }
        @media print {
            body {
                background-color: #ffffff;
            }
            .invoice-box {
                box-shadow: none;
                margin: 0;
                padding: 20px;
                max-width: 100%;
                border-top: none;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
@php
    $kodeBooking = $reservasi->kode_booking ?? $reservasi->kode;
    $namaStudio = $reservasi->studio->nama ?? $reservasi->studio->nama_studio ?? '-';
    $totalBayar = $reservasi->total_bayar ?: $reservasi->total_harga;
    $subtotal = $reservasi->subtotal ?: $totalBayar;
@endphp

<div class="container">
    <!-- Tombol Aksi (Tersembunyi saat dicetak) -->
    <div class="d-flex justify-content-end gap-2 mt-4 no-print" style="max-width: 800px; margin: 20px auto 0;">
        <button onclick="window.print()" class="btn btn-dark fw-semibold">
            Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" class="btn btn-outline-secondary">
            Tutup
        </button>
    </div>

    <div class="invoice-box">
        <!-- Header Invoice -->
        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Studio Musik Positif</h2>
                <p class="text-muted small mb-0">Bukti Pembayaran & Reservasi Resmi</p>
            </div>
            <div class="text-end">
                <h4 class="fw-bold text-warning text-uppercase mb-1">INVOICE</h4>
                <div class="fw-bold fs-6">#{{ $kodeBooking }}</div>
                <span class="badge bg-success mt-1">{{ strtoupper($reservasi->status) }}</span>
            </div>
        </div>

        <!-- Info Pelanggan & Transaksi -->
        <div class="row mb-4">
            <div class="col-6">
                <div class="text-muted small mb-1">Ditagihkan Kepada:</div>
                <div class="fw-bold fs-6">{{ $reservasi->user->name ?? '-' }}</div>
                <div class="text-muted small">{{ $reservasi->user->email ?? '-' }}</div>
                @if(!empty($reservasi->user->no_hp))
                    <div class="text-muted small">{{ $reservasi->user->no_hp }}</div>
                @endif
            </div>
            <div class="col-6 text-end">
                <div class="text-muted small mb-1">Tanggal Cetak:</div>
                <div class="fw-semibold">{{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
                <div class="text-muted small mt-2 mb-1">Metode Pembayaran:</div>
                <div class="fw-semibold">{{ $reservasi->pembayaran->metode_pembayaran ?? 'Transfer Bank' }}</div>
            </div>
        </div>

        <!-- Tabel Rincian -->
        <table class="table table-bordered align-middle mb-4">
            <thead class="table-light">
                <tr>
                    <th>Deskripsi Layanan</th>
                    <th>Jadwal Main</th>
                    <th class="text-center">Durasi</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="fw-bold">{{ $namaStudio }}</div>
                        @if($reservasi->catatan)
                            <small class="text-muted">Catatan: {{ $reservasi->catatan }}</small>
                        @endif
                    </td>
                    <td>
                        <div>{{ \Carbon\Carbon::parse($reservasi->tanggal)->translatedFormat('l, d F Y') }}</div>
                        <small class="text-muted">{{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }} WIB</small>
                    </td>
                    <td class="text-center">{{ $reservasi->durasi_jam }} Jam</td>
                    <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end fw-normal">Subtotal</th>
                    <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th colspan="3" class="text-end fw-normal text-success">
                        Diskon {{ $reservasi->promo ? '(' . $reservasi->promo->kode . ')' : '' }}
                    </th>
                    <td class="text-end text-success">- Rp {{ number_format($reservasi->diskon ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr class="table-warning">
                    <th colspan="3" class="text-end fs-5">Total Dibayar</th>
                    <th class="text-end fs-5">Rp {{ number_format($totalBayar, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <!-- Catatan Kaki -->
        <div class="text-center text-muted small mt-5 pt-3 border-top">
            Terima kasih telah melakukan reservasi di <strong>Studio Musik Positif</strong>.<br>
            Harap tunjukkan invoice ini kepada petugas studio saat kedatangan.
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>