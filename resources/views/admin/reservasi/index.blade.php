@extends('layouts.admin')

@section('title', 'Kelola Reservasi - Admin')

@push('styles')
<style>
    .admin-card {
        border-radius: 1rem;
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        background: #ffffff;
    }
    .table-admin thead th {
        background-color: #16181d;
        color: #f8f9fa;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border: none;
        padding: 0.9rem 1rem;
    }
    .table-admin tbody td {
        padding: 0.95rem 1rem;
        border-bottom: 1px solid #f1f3f5;
        vertical-align: middle;
    }
    .table-admin tbody tr:hover {
        background-color: rgba(255, 193, 7, 0.04);
    }
    .status-select-pill {
        font-size: 0.82rem;
        font-weight: 600;
        border-radius: 999px;
        padding: 0.35rem 2rem 0.35rem 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .status-waiting-pay {
        background-color: rgba(255, 193, 7, 0.16);
        color: #856404;
        border: 1px solid rgba(255, 193, 7, 0.45);
    }
    .status-waiting-verify {
        background-color: rgba(13, 202, 240, 0.16);
        color: #055160;
        border: 1px solid rgba(13, 202, 240, 0.45);
    }
    .status-confirmed {
        background-color: rgba(13, 110, 253, 0.14);
        color: #084298;
        border: 1px solid rgba(13, 110, 253, 0.35);
    }
    .status-done {
        background-color: rgba(25, 135, 84, 0.15);
        color: #0f5132;
        border: 1px solid rgba(25, 135, 84, 0.35);
    }
    .status-cancelled {
        background-color: rgba(220, 53, 69, 0.14);
        color: #842029;
        border: 1px solid rgba(220, 53, 69, 0.35);
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Data Seluruh Reservasi</h3>
        <p class="text-muted small mb-0">Cari jadwal pelanggan dan perbarui status sesi dengan satu klik.</p>
    </div>
    <span class="badge bg-dark text-warning px-3 py-2 rounded-pill border border-warning border-opacity-25">
        <i class="bi bi-calendar-check me-1"></i> Total: {{ $reservasis->total() }} Reservasi
    </span>
</div>

<!-- Filter Ringkas -->
<div class="admin-card p-3 p-md-4 mb-4">
    <form action="{{ route('admin.reservasi.index') }}" method="GET" class="row g-2 g-md-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-semibold small text-muted mb-1">Cari Kode / Pelanggan</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0"
                       placeholder="Contoh: SMP-2026 atau Rafi..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold small text-muted mb-1">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                @foreach(['Menunggu pembayaran', 'Menunggu verifikasi', 'Dikonfirmasi', 'Selesai', 'Dibatalkan'] as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold small text-muted mb-1">Tanggal Main</label>
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-warning fw-bold text-dark w-100">Filter</button>
            @if(request()->hasAny(['search', 'status', 'tanggal']) && (request('search') || request('status') || request('tanggal')))
                <a href="{{ route('admin.reservasi.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Reservasi Sederhana & Modern -->
<div class="admin-card overflow-hidden">
    <div class="table-responsive">
        <table class="table table-admin mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Kode & Pelanggan</th>
                    <th>Studio & Jadwal</th>
                    <th>Total Bayar</th>
                    <th>Status Sesi</th>
                    <th class="text-end pe-4">Hapus</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservasis as $res)
                    @php
                        $selectClass = match($res->status) {
                            'Menunggu pembayaran' => 'status-waiting-pay',
                            'Menunggu verifikasi' => 'status-waiting-verify',
                            'Dikonfirmasi' => 'status-confirmed',
                            'Selesai' => 'status-done',
                            'Dibatalkan' => 'status-cancelled',
                            default => ''
                        };
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $res->kode_booking ?? $res->kode }}</div>
                            <div class="small text-muted">
                                <i class="bi bi-person me-1"></i>{{ $res->user->name ?? '-' }}
                                @if(!empty($res->user->no_hp))
                                    <span class="mx-1">•</span>{{ $res->user->no_hp }}
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $res->studio->nama ?? '-' }}</div>
                            <div class="small text-muted">
                                <i class="bi bi-calendar3 me-1 text-warning"></i>{{ \Carbon\Carbon::parse($res->tanggal)->format('d M Y') }}
                                <span class="mx-1">•</span>
                                {{ substr($res->jam_mulai, 0, 5) }}–{{ substr($res->jam_selesai, 0, 5) }} ({{ $res->durasi_jam }} jam)
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">
                                Rp {{ number_format($res->total_bayar ?: $res->total_harga, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.reservasi.status', $res) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <select name="status"
                                        onchange="this.form.submit()"
                                        class="form-select form-select-sm status-select-pill {{ $selectClass }}"
                                        style="width: auto; min-width: 190px;"
                                        title="Pilih untuk langsung mengubah status">
                                    @foreach(['Menunggu pembayaran', 'Menunggu verifikasi', 'Dikonfirmasi', 'Selesai', 'Dibatalkan'] as $st)
                                        <option value="{{ $st }}" class="bg-white text-dark" {{ $res->status === $st ? 'selected' : '' }}>
                                            {{ $st }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="text-end pe-4">
                            <form action="{{ route('admin.reservasi.destroy', $res) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus permanen reservasi {{ $res->kode_booking ?? $res->kode }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3" title="Hapus Reservasi">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                            Tidak ada data reservasi yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($reservasis->hasPages())
    <div class="mt-4 d-flex justify-content-center">{{ $reservasis->links() }}</div>
@endif
@endsection