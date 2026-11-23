@extends('layouts.app')

@section('title', 'Login - Studio Musik Positif')

@section('content')
<style>
    .login-wrapper {
        min-height: 78vh;
        display: flex;
        align-items: center;
    }
    .login-card {
        border-radius: 1.25rem;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
        background: #ffffff;
    }
    .login-brand-panel {
        background: radial-gradient(circle at top right, #2c2514 0%, #16181d 55%, #0d0e12 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .login-brand-panel::before {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        background: rgba(255, 193, 7, 0.12);
        filter: blur(70px);
        top: -60px;
        right: -60px;
        border-radius: 50%;
    }
    .brand-badge {
        background: rgba(255, 193, 7, 0.15);
        border: 1px solid rgba(255, 193, 7, 0.35);
        color: #ffc107;
        font-size: 0.78rem;
        letter-spacing: 0.5px;
    }
    .feature-item {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
    }
    .form-control-lg-custom {
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        border-color: #dee2e6;
    }
    .form-control-lg-custom:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.2);
    }
    .input-group-text-custom {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
    }
    .btn-login-submit {
        background: linear-gradient(135deg, #1a1d24 0%, #0f1115 100%);
        border: none;
        color: #ffc107;
        padding: 0.8rem;
        border-radius: 0.65rem;
        transition: all 0.25s ease;
    }
    .btn-login-submit:hover {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #111317;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(255, 193, 7, 0.3);
    }
</style>

<div class="container login-wrapper my-4">
    <div class="row justify-content-center w-100 mx-0">
        <div class="col-xl-10 col-lg-11 px-0">
            <div class="card border-0 login-card">
                <div class="row g-0">
                    <!-- Panel Kiri: Visual & Branding Studio -->
                    <div class="col-lg-6 login-brand-panel p-4 p-md-5 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge rounded-pill brand-badge px-3 py-2 mb-4">
                                <i class="bi bi-soundwave me-1"></i> STUDIO MUSIK POSITIF
                            </span>
                            <h2 class="fw-bold display-6 mb-3" style="line-height: 1.2;">
                                Wujudkan Karya & Latihan Terbaikmu.
                            </h2>
                            <p class="text-white-50 mb-4">
                                Pesan ruangan studio dengan akustik premium, peralatan berstandar panggung, dan jadwal real-time tanpa antre.
                            </p>
                        </div>

                        <div class="d-flex flex-column gap-2 my-3">
                            <div class="feature-item d-flex align-items-center gap-3">
                                <div class="text-warning fs-4"><i class="bi bi-calendar2-check"></i></div>
                                <div>
                                    <div class="fw-semibold small">Booking Instan & Transparan</div>
                                    <div class="text-white-50" style="font-size: 0.78rem;">Cek ketersediaan jam studio secara langsung</div>
                                </div>
                            </div>
                            <div class="feature-item d-flex align-items-center gap-3">
                                <div class="text-warning fs-4"><i class="bi bi-speaker"></i></div>
                                <div>
                                    <div class="fw-semibold small">Perangkat & Akustik Terawat</div>
                                    <div class="text-white-50" style="font-size: 0.78rem;">Pilihan ruang Deluxe Acoustic hingga Full Band</div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-top border-secondary border-opacity-25 small text-white-50">
                            &copy; {{ date('Y') }} Studio Musik Positif. All rights reserved.
                        </div>
                    </div>

                    <!-- Panel Kanan: Form Login -->
                    <div class="col-lg-6 p-4 p-md-5 d-flex flex-column justify-content-center bg-white">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-3 p-2 mb-3">
                                <i class="bi bi-music-note-beamed fs-3"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">Selamat Datang Kembali</h3>
                            <p class="text-muted small mb-0">Masuk ke akun Anda untuk mengelola jadwal reservasi studio</p>
                        </div>

                        <form action="{{ route('login') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold small text-dark">Alamat Email</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-custom">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           placeholder="nama@email.com"
                                           class="form-control form-control-lg-custom @error('email') is-invalid @enderror"
                                           required
                                           autofocus>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold small text-dark">Kata Sandi</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-custom">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password"
                                           id="password"
                                           name="password"
                                           placeholder="Masukkan kata sandi"
                                           class="form-control form-control-lg-custom @error('password') is-invalid @enderror"
                                           required>
                                    <button type="button" class="btn btn-outline-secondary border-start-0" style="border-color: #dee2e6;" onclick="togglePassword()" title="Lihat/Sembunyikan Kata Sandi">
                                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label small text-muted" for="remember">Ingat sesi saya</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-login-submit w-100 fw-bold">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Akun
                            </button>
                        </form>

                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="small text-muted mb-0">
                                Belum punya akun Studio Musik Positif?
                                <a href="{{ route('register') }}" class="fw-bold text-dark text-decoration-none border-bottom border-warning border-2 pb-1 ms-1">
                                    Daftar Sekarang
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
@endsection