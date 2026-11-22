@extends('layouts.admin')

@section('title', 'Edit Studio ' . $studio->nama . ' - Admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.studio.index') }}" class="text-decoration-none small"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Studio</a>
    <h3 class="fw-bold mt-1 mb-0">Edit Studio: {{ $studio->nama }}</h3>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.studio.update', $studio) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Studio</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $studio->nama) }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kapasitas (Orang)</label>
                    <input type="number" name="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" value="{{ old('kapasitas', $studio->kapasitas) }}" min="1" required>
                    @error('kapasitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Harga per Jam (Rp)</label>
                    <input type="number" name="harga_per_jam" class="form-control @error('harga_per_jam') is-invalid @enderror" value="{{ old('harga_per_jam', $studio->harga_per_jam) }}" min="0" step="5000" required>
                    @error('harga_per_jam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi & Spesifikasi Alat</label>
                    <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi', $studio->deskripsi) }}</textarea>
                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold d-block">Pilih Fasilitas Tersedia</label>
                    @php $selectedFas = old('fasilitas', $studio->fasilitas->pluck('id')->toArray()); @endphp
                    <div class="row g-2">
                        @foreach($fasilitas as $fas)
                            <div class="col-md-3">
                                <div class="form-check border rounded p-2 ps-4 bg-light">
                                    <input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fas->id }}" id="fas{{ $fas->id }}"
                                           {{ in_array($fas->id, $selectedFas) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="fas{{ $fas->id }}">{{ $fas->nama }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Tambah Foto Baru (Opsional)</label>
                    <input type="file" name="fotos[]" class="form-control" accept="image/*" multiple>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.studio.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Galeri Foto Saat Ini -->
@if($studio->fotos->isNotEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0">Galeri Foto Studio Saat Ini</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($studio->fotos as $foto)
                <div class="col-md-3">
                    <div class="border rounded p-2 text-center">
                        <img src="{{ asset('storage/' . ($foto->path ?? $foto->path_foto)) }}" class="img-fluid rounded mb-2" style="height: 140px; width: 100%; object-fit: cover;" alt="Foto Studio">
                        <form action="{{ route('admin.studio.hapus_foto', $foto) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100"><i class="bi bi-trash me-1"></i> Hapus Foto</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection