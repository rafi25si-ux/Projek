@extends('layouts.admin')

@section('title', 'Kelola Kode Promo - Admin')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Kelola Kode Promo</h3>
    <p class="text-muted mb-0">Buat dan atur masa berlaku voucher diskon untuk pelanggan.</p>
</div>

<div class="row g-4">
    <!-- Form Tambah Promo -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white py-3">
                <h6 class="fw-bold mb-0">Buat Promo Baru</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.promo.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Promo</label>
                        <input type="text" name="kode" class="form-control text-uppercase @error('kode') is-invalid @enderror" required placeholder="Contoh: MUSIKHEMAT20">
                        @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipe Potongan</label>
                        <select name="tipe_potongan" class="form-select" required>
                            <option value="persen">Persentase (%)</option>
                            <option value="nominal">Nominal Rupiah (Rp)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nilai Potongan</label>
                        <input type="number" name="potongan" class="form-control @error('potongan') is-invalid @enderror" min="1" required placeholder="Contoh: 15 atau 25000">
                        @error('potongan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Berlaku Mulai</label>
                            <input type="date" name="berlaku_mulai" class="form-control" value="{{ now()->toDateString() }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Berlaku Sampai</label>
                            <input type="date" name="berlaku_sampai" class="form-control" value="{{ now()->addDays(30)->toDateString() }}" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning text-dark fw-bold w-100">Simpan Promo</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Promo -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Kode</th>
                                <th>Potongan</th>
                                <th>Masa Berlaku</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promos as $promo)
                                @php
                                    $aktif = now()->between(\Carbon\Carbon::parse($promo->berlaku_mulai)->startOfDay(), \Carbon\Carbon::parse($promo->berlaku_sampai)->endOfDay());
                                @endphp
                                <tr>
                                    <td class="ps-4 fw-bold text-primary">{{ $promo->kode }}</td>
                                    <td>
                                        {{ $promo->tipe_potongan === 'persen' ? $promo->potongan . '%' : 'Rp ' . number_format($promo->potongan, 0, ',', '.') }}
                                    </td>
                                    <td class="small">
                                        {{ \Carbon\Carbon::parse($promo->berlaku_mulai)->format('d M Y') }} &ndash;
                                        {{ \Carbon\Carbon::parse($promo->berlaku_sampai)->format('d M Y') }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $aktif ? 'bg-success' : 'bg-secondary' }}">{{ $aktif ? 'Aktif' : 'Nonaktif / Expired' }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('admin.promo.destroy', $promo) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kode promo ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada kode promo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-3 d-flex justify-content-center">{{ $promos->links() }}</div>
    </div>
</div>
@endsection