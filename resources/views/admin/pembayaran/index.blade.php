@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Verifikasi Pembayaran</h3>
        <p class="text-muted mb-0">Periksa bukti transfer dari pelanggan sebelum mengonfirmasi jadwal studio.</p>
    </div>
    <form action="{{ route('admin.pembayaran.index') }}" method="GET" class="d-flex gap-2">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach(['Menunggu verifikasi', 'Disetujui', 'Ditolak'] as $st)
                <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode Booking</th>
                        <th>Pelanggan & Studio</th>
                        <th>Metode & Jumlah</th>
                        <th>Bukti Transfer</th>
                        <th>Status</th>
                        <th class="pe-4">Verifikasi & Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $bayar)
                        @php
                            $badge = match($bayar->status) {
                                'Menunggu verifikasi' => 'bg-warning text-dark',
                                'Disetujui' => 'bg-success',
                                'Ditolak' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <tr>
                            <td class="ps-4 fw-bold">{{ $bayar->reservasi->kode_booking ?? '-' }}</td>
                            <td>
                                <div>{{ $bayar->reservasi->user->name ?? '-' }}</div>
                                <small class="text-muted">{{ $bayar->reservasi->studio->nama ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</div>
                                <small class="text-muted">{{ $bayar->metode_pembayaran }}</small>
                            </td>
                            <td>
                                <a href="{{ asset('storage/' . $bayar->bukti_path) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                    <i class="bi bi-image me-1"></i> Lihat Bukti
                                </a>
                            </td>
                            <td>
                                <span class="badge {{ $badge }}">{{ $bayar->status }}</span>
                                @if($bayar->alasan_penolakan)
                                    <div class="small text-danger mt-1">{{ $bayar->alasan_penolakan }}</div>
                                @endif
                            </td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-2">
                                    @if($bayar->status === 'Menunggu verifikasi')
                                        <div class="d-flex flex-column gap-1">
                                            <form action="{{ route('admin.pembayaran.verifikasi', $bayar) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="aksi" value="terima">
                                                <button type="submit" class="btn btn-sm btn-success w-100" onclick="return confirm('Setujui pembayaran ini?')">
                                                    <i class="bi bi-check-lg"></i> Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.pembayaran.verifikasi', $bayar) }}" method="POST" class="d-flex gap-1">
                                                @csrf
                                                <input type="hidden" name="aksi" value="tolak">
                                                <input type="text" name="alasan_penolakan" class="form-control form-control-sm" placeholder="Alasan tolak..." required>
                                                <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted small">Selesai diproses</span>
                                    @endif

                                    <form action="{{ route('admin.pembayaran.destroy', $bayar) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data pembayaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Pembayaran">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data pembayaran masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">{{ $pembayarans->links() }}</div>
@endsection