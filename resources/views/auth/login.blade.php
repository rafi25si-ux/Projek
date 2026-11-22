@extends('layouts.app')

@section('title', 'Login - Studio Musik Positif')

@section('content')
<div class="row justify-content-center my-5">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-music-note-beamed fs-1 text-warning"></i>
                    <h4 class="fw-bold mt-2">Masuk ke Akun Anda</h4>
                    <p class="text-muted small">Silakan login untuk memesan jadwal studio musik</p>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kata Sandi</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Masukkan kata sandi">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label small" for="remember">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn btn-dark w-100 fw-semibold py-2">Masuk</button>
                </form>

                <div class="text-center mt-4">
                    <p class="small text-muted mb-0">Belum punya akun? <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Daftar sekarang</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection