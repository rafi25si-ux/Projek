@extends('layouts.admin')

@section('title', 'Dashboard Admin - Studio Musik Positif')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Dashboard Admin</h3>
        <p class="text-muted mb-0">Ringkasan operasional Studio Musik Positif hari ini.</p>
    </div>
    <span class="badge bg-dark px-3 py-2">{{ now()->translatedFormat('l, d F Y') }}</span>
</div>

<!-- Kartu Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Total Studio</div>
                <h2 class="fw-bold mt-2 mb-0">{{ $totalStudio }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Total Pelanggan</div>
                <h2 class="fw-bold mt-2 mb-0">{{ $totalPelanggan }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Perlu Verifikasi Bayar</div>
                <h2 class="fw-bold mt-2 mb-0 text-warning">{{ $perluVerifikasi }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-4 border-info">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Total Pendapatan</div>
                <h4 class="fw-bold mt-2 mb-0 text-primary">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Reservasi Terbaru -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">Reservasi Terbaru ({{ $totalReservasi }} Total)</h6>
        <a href="{{ route('admin.reservasi.index') }}" class="btn btn-sm btn-outline-dark">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Pelanggan</th>
                        <th>Studio</th>
                        <th>Jadwal</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasiTerbaru as $res)
                        @php
                            $badge = match($res->status) {
                                'Menunggu pembayaran' => 'bg-warning text-dark',
                                'Menunggu verifikasi' => 'bg-info text-dark',
                                'Dikonfirmasi' => 'bg-primary',
                                'Selesai' => 'bg-success',
                                'Dibatalkan' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <tr>
                            <td class="ps-4 fw-bold">{{ $res->kode_booking }}</td>
                            <td>{{ $res->user->name ?? '-' }}</td>
                            <td>{{ $res->studio->nama ?? '-' }}</td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($res->tanggal)->format('d M Y') }}</div>
                                <small class="text-muted">{{ substr($res->jam_mulai, 0, 5) }} - {{ substr($res->jam_selesai, 0, 5) }}</small>
                            </td>
                            <td>Rp {{ number_format($res->total_bayar, 0, ',', '.') }}</td>
                            <td><span class="badge {{ $badge }}">{{ $res->status }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data reservasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection