@extends('layouts.admin')

@section('title', 'Kelola Fasilitas - Admin')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Master Fasilitas & Peralatan</h3>
    <p class="text-muted mb-0">Kelola daftar fasilitas standar yang dapat dipilih untuk setiap studio.</p>
</div>

<div class="row g-4">
    <!-- Form Tambah -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white py-3">
                <h6 class="fw-bold mb-0">Tambah Fasilitas Baru</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.fasilitas.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Fasilitas</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" required placeholder="Contoh: AC Ruangan, Drum Tama, dll">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-warning text-dark fw-bold w-100">Simpan Fasilitas</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Fasilitas -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nama Fasilitas</th>
                            <th>Dipakai di Studio</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fasilitas as $item)
                            <tr>
                                <td class="ps-4">
                                    <form action="{{ route('admin.fasilitas.update', $item) }}" method="POST" class="d-flex gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="nama" class="form-control form-control-sm" value="{{ $item->nama }}" required>
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                    </form>
                                </td>
                                <td><span class="badge bg-secondary">{{ $item->studios_count }} Studio</span></td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('admin.fasilitas.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus fasilitas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Belum ada data fasilitas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3 d-flex justify-content-center">{{ $fasilitas->links() }}</div>
    </div>
</div>
@endsection