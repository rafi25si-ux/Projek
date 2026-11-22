@extends('layouts.app')

@section('title', 'Riwayat Reservasi - Studio Musik Positif')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Riwayat Reservasi Saya</h2>
        <p class="text-muted mb-0">Pantau status pesanan, unggah bukti pembayaran, dan cetak invoice.</p>
    </div>
    <a href="{{ route('katalog') }}" class="btn btn-dark"><i class="bi bi-plus-lg me-1"></i> Booking Baru</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode Booking</th>
                        <th>Studio</th>
                        <th>Jadwal</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasis as $res)
                        @php
                            $badgeClass = match($res->status) {
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
                            <td>{{ $res->studio->nama ?? $res->studio->nama_studio ?? '-' }}</td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($res->tanggal)->format('d M Y') }}</div>
                                <small class="text-muted">{{ substr($res->jam_mulai, 0, 5) }} - {{ substr($res->jam_selesai, 0, 5) }} ({{ $res->durasi_jam }} jam)</small>
                            </td>
                            <td class="fw-semibold">Rp {{ number_format($res->total_bayar, 0, ',', '.') }}</td>
                            <td><span class="badge {{ $badgeClass }}">{{ $res->status }}</span></td>
                            <td class="text-end pe-4">
                                <a href="{{ route('riwayat.detail', $res) }}" class="btn btn-sm btn-outline-dark">
                                    Detail
                                </a>
                                @if(in_array($res->status, ['Dikonfirmasi', 'Selesai']))
                                    <a href="{{ route('riwayat.invoice', $res) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="bi bi-printer"></i> Invoice
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                Anda belum memiliki riwayat reservasi studio.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $reservasis->links() }}
</div>
@endsection