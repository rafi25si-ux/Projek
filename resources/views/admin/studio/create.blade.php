@extends('layouts.admin')

@section('title', 'Tambah Studio Baru - Admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.studio.index') }}" class="text-decoration-none small"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Studio</a>
    <h3 class="fw-bold mt-1 mb-0">Tambah Studio Musik Baru</h3>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.studio.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Studio</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required placeholder="Contoh: Studio A - Premium Band">
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kapasitas (Orang)</label>
                    <input type="number" name="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" value="{{ old('kapasitas', 6) }}" min="1" required>
                    @error('kapasitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Harga per Jam (Rp)</label>
                    <input type="number" name="harga_per_jam" class="form-control @error('harga_per_jam') is-invalid @enderror" value="{{ old('harga_per_jam', 100000) }}" min="0" step="5000" required>
                    @error('harga_per_jam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi & Spesifikasi Alat</label>
                    <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" required placeholder="Jelaskan spesifikasi akustik, amplifier, drum set, mixer, dll.">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold d-block">Pilih Fasilitas Tersedia</label>
                    <div class="row g-2">
                        @forelse($fasilitas as $fas)
                            <div class="col-md-3">
                                <div class="form-check border rounded p-2 ps-4 bg-light">
                                    <input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fas->id }}" id="fas{{ $fas->id }}"
                                           {{ in_array($fas->id, old('fasilitas', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="fas{{ $fas->id }}">{{ $fas->nama }}</label>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-muted small">Belum ada master fasilitas. Tambahkan di menu Fasilitas terlebih dahulu.</div>
                        @endforelse
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Upload Foto Studio (Bisa pilih lebih dari satu)</label>
                    <input type="file" name="fotos[]" class="form-control @error('fotos.*') is-invalid @enderror" accept="image/*" multiple>
                    <small class="text-muted">Format: JPG, PNG, WEBP. Maksimal 2MB per foto.</small>
                    @error('fotos.*')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.studio.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-dark px-4">Simpan Studio</button>
            </div>
        </form>
    </div>
</div>
@endsection