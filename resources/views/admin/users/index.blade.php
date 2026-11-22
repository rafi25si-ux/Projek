@extends('layouts.admin')

@section('title', 'Kelola Pengguna - Admin')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Daftar Akun Pelanggan</h3>
    <p class="text-muted mb-0">Pantau data pelanggan terdaftar dan kelola status aktif akun.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nama Lengkap</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Total Reservasi</th>
                        <th>Status Akun</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->no_hp ?? '-' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $user->reservasis_count }} Kali</span></td>
                            <td>
                                <span class="badge {{ $user->status === 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.users.toggle_status', $user) }}" method="POST" onsubmit="return confirm('Ubah status akun pengguna ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $user->status === 'aktif' ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                        {{ $user->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada pelanggan terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">{{ $users->links() }}</div>
@endsection