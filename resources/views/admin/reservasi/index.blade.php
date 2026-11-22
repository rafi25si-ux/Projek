@extends('layouts.admin')

@section('title', 'Kelola Reservasi - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Data Seluruh Reservasi</h3>
        <p class="text-muted mb-0">Kelola jadwal pemesanan studio dan perbarui status sesi pelanggan.</p>
    </div>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.reservasi.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Filter Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(['Menunggu pembayaran', 'Menunggu verifikasi', 'Dikonfirmasi', 'Selesai', 'Dibatalkan'] as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Filter Tanggal Main</label>
                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark">Filter</button>
                <a href="{{ route('admin.reservasi.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Reservasi -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode Booking</th>
                        <th>Pelanggan</th>
                        <th>Studio</th>
                        <th>Jadwal</th>
                        <th>Total Bayar</th>
                        <th>Status Saat Ini</th>
                        <th class="pe-4">Ubah Status & Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasis as $res)
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
                            <td class="ps-4 fw-bold">{{ $res->kode_booking ?? $res->kode }}</td>
                            <td>
                                <div>{{ $res->user->name ?? '-' }}</div>
                                <small class="text-muted">{{ $res->user->no_hp ?? '' }}</small>
                            </td>
                            <td>{{ $res->studio->nama ?? '-' }}</td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($res->tanggal)->format('d M Y') }}</div>
                                <small class="text-muted">{{ substr($res->jam_mulai, 0, 5) }} - {{ substr($res->jam_selesai, 0, 5) }} ({{ $res->durasi_jam }} jam)</small>
                            </td>
                            <td class="fw-semibold">Rp {{ number_format($res->total_bayar ?: $res->total_harga, 0, ',', '.') }}</td>
                            <td><span class="badge {{ $badge }}">{{ $res->status }}</span></td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-1">
                                    <form action="{{ route('admin.reservasi.status', $res) }}" method="POST" class="d-flex gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm" style="width: 170px;">
                                            @foreach(['Menunggu pembayaran', 'Menunggu verifikasi', 'Dikonfirmasi', 'Selesai', 'Dibatalkan'] as $st)
                                                <option value="{{ $st }}" {{ $res->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-dark">Simpan</button>
                                    </form>

                                    @if(in_array($res->status, ['Menunggu pembayaran', 'Dibatalkan']))
                                        <form action="{{ route('admin.reservasi.destroy', $res) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus permanen reservasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus Reservasi">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada data reservasi ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">{{ $reservasis->links() }}</div>
@endsection