@extends('layouts.admin')

@section('title', 'Kelola Studio - Admin')

@section('content')
<style>
    .admin-table-card {
        border-radius: 16px;
        overflow: hidden;
    }
    .studio-thumb-wrapper {
        width: 84px;
        height: 56px;
        border-radius: 10px;
        overflow: hidden;
        background-color: #181a1f;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }
    .studio-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: saturate(0.85) contrast(0.96);
        transition: transform 0.35s ease, filter 0.3s ease;
    }
    tr:hover .studio-thumb-img {
        transform: scale(1.08);
        filter: saturate(0.95) contrast(1);
    }
    .table-studio-modern tbody tr {
        transition: background-color 0.2s ease;
    }
    .table-studio-modern tbody tr:hover {
        background-color: rgba(255, 193, 7, 0.04);
    }
    .badge-fasilitas-admin {
        background-color: #f4f4f6;
        color: #3f3f46;
        font-weight: 500;
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        border-radius: 8px;
    }
    .btn-tambah-modern {
        border-radius: 10px;
        transition: all 0.25s ease;
    }
    .btn-tambah-modern:hover {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #181a1f;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 193, 7, 0.3);
    }
    .btn-aksi-modern {
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-aksi-modern:hover {
        transform: translateY(-1px);
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Data Studio Musik</h3>
        <p class="text-muted mb-0">Kelola daftar ruangan studio, tarif sewa, fasilitas, dan galeri foto.</p>
    </div>
    <a href="{{ route('admin.studio.create') }}" class="btn btn-dark fw-semibold px-3 py-2 btn-tambah-modern">
        <i class="bi bi-plus-lg me-1"></i> Tambah Studio
    </a>
</div>

<div class="card admin-table-card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-studio-modern align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Foto</th>
                        <th>Nama Studio</th>
                        <th>Kapasitas</th>
                        <th>Harga / Jam</th>
                        <th>Fasilitas</th>
                        <th class="text-end pe-4 text-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studios as $studio)
                        @php
                            $namaStudio = $studio->nama ?? $studio->nama_studio;
                            $foto = $studio->fotos->firstWhere('is_utama', true) ?? $studio->fotos->first();
                            $pathFoto = $foto ? ($foto->path_foto ?? $foto->path ?? $foto->foto_path ?? $foto->url) : null;

                            $fotoDefault = match(true) {
                                str_contains(strtolower($namaStudio), 'studio a') => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?auto=format&fit=crop&w=400&q=80',
                                str_contains(strtolower($namaStudio), 'studio b') => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=400&q=80',
                                str_contains(strtolower($namaStudio), 'studio c') => 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?auto=format&fit=crop&w=400&q=80',
                                str_contains(strtolower($namaStudio), 'studio d') => 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=400&q=80',
                                str_contains(strtolower($namaStudio), 'studio e') => 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?auto=format&fit=crop&w=400&q=80',
                                default => 'https://images.unsplash.com/photo-1507838153414-b4b713384a76?auto=format&fit=crop&w=400&q=80',
                            };
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="studio-thumb-wrapper">
                                    <img src="{{ $pathFoto ? asset('storage/' . $pathFoto) : $fotoDefault }}"
                                         class="studio-thumb-img"
                                         alt="{{ $namaStudio }}">
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $namaStudio }}</div>
                                <small class="text-muted">{{ Str::limit($studio->deskripsi, 50) }}</small>
                            </td>
                            <td class="text-nowrap">{{ $studio->kapasitas }} Orang</td>
                            <td class="fw-semibold text-nowrap">Rp {{ number_format($studio->harga_per_jam, 0, ',', '.') }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($studio->fasilitas->take(3) as $fas)
                                        <span class="badge-fasilitas-admin">{{ $fas->nama_fasilitas ?? $fas->nama }}</span>
                                    @endforeach
                                    @if($studio->fasilitas->count() > 3)
                                        <span class="badge-fasilitas-admin text-muted">+{{ $studio->fasilitas->count() - 3 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end pe-4 text-nowrap">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <a href="{{ route('admin.studio.edit', $studio) }}" class="btn btn-sm btn-outline-primary btn-aksi-modern">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.studio.destroy', $studio) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin menghapus studio ini beserta seluruh fotonya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-aksi-modern">
                                            <i class="bi bi-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada data studio.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $studios->links() }}
</div>
@endsection