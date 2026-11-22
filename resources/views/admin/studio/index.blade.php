@extends('layouts.admin')

@section('title', 'Kelola Studio - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Data Studio Musik</h3>
        <p class="text-muted mb-0">Kelola daftar ruangan studio, tarif sewa, fasilitas, dan galeri foto.</p>
    </div>
    <a href="{{ route('admin.studio.create') }}" class="btn btn-dark"><i class="bi bi-plus-lg me-1"></i> Tambah Studio</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
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
                        @php $foto = $studio->fotos->first(); @endphp
                        <tr>
                            <td class="ps-4">
                                <img src="{{ $foto ? asset('storage/' . ($foto->path ?? $foto->path_foto)) : 'https://placehold.co/80x60/212529/ffc107?text=Studio' }}"
                                     class="rounded" width="80" height="55" style="object-fit: cover;" alt="{{ $studio->nama }}">
                            </td>
                            <td>
                                <div class="fw-bold">{{ $studio->nama }}</div>
                                <small class="text-muted">{{ Str::limit($studio->deskripsi, 50) }}</small>
                            </td>
                            <td class="text-nowrap">{{ $studio->kapasitas }} Orang</td>
                            <td class="fw-semibold text-nowrap">Rp {{ number_format($studio->harga_per_jam, 0, ',', '.') }}</td>
                            <td>
                                @foreach($studio->fasilitas->take(3) as $fas)
                                    <span class="badge bg-light text-dark border">{{ $fas->nama }}</span>
                                @endforeach
                                @if($studio->fasilitas->count() > 3)
                                    <span class="badge bg-secondary">+{{ $studio->fasilitas->count() - 3 }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-4 text-nowrap">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <a href="{{ route('admin.studio.edit', $studio) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.studio.destroy', $studio) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin menghapus studio ini beserta seluruh fotonya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data studio.</td>
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