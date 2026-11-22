@extends('layouts.app')

@section('title', 'Daftar Akun - Studio Musik Positif')

@section('content')
<div class="row justify-content-center my-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-person-plus-fill fs-1 text-warning"></i>
                    <h4 class="fw-bold mt-2">Buat Akun Pelanggan</h4>
                    <p class="text-muted small">Daftar gratis dan mulai booking studio favoritmu</p>
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="nama@email.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}" required placeholder="081234567890">
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kata Sandi</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Minimal 6 karakter">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi kata sandi">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning text-dark w-100 fw-bold py-2 mt-2">Daftar Sekarang</button>
                </form>

                <div class="text-center mt-4">
                    <p class="small text-muted mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Masuk di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection