@extends('layouts.app')

@section('title', 'Reset Kata Sandi - Studio Musik Positif')

@section('content')
<style>
    .reset-wrapper {
        min-height: 78vh;
        display: flex;
        align-items: center;
    }
    .reset-card {
        border-radius: 1.25rem;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
        background: #ffffff;
    }
    .reset-brand-panel {
        background: radial-gradient(circle at top right, #2c2514 0%, #16181d 55%, #0d0e12 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .reset-brand-panel::before {
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
    .btn-reset-submit {
        background: linear-gradient(135deg, #1a1d24 0%, #0f1115 100%);
        border: none;
        color: #ffc107;
        padding: 0.8rem;
        border-radius: 0.65rem;
        transition: all 0.25s ease;
    }
    .btn-reset-submit:hover {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #111317;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(255, 193, 7, 0.3);
    }
</style>

<div class="container reset-wrapper my-4">
    <div class="row justify-content-center w-100 mx-0">
        <div class="col-xl-10 col-lg-11 px-0">
            <div class="card border-0 reset-card">
                <div class="row g-0">
                    <!-- Panel Kiri: Panduan Singkat -->
                    <div class="col-lg-5 reset-brand-panel p-4 p-md-5 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge rounded-pill brand-badge px-3 py-2 mb-4">
                                <i class="bi bi-shield-lock me-1"></i> PEMULIHAN AKUN
                            </span>
                            <h2 class="fw-bold h3 mb-3" style="line-height: 1.3;">
                                Atur Ulang Kata Sandi dengan Cepat.
                            </h2>
                            <p class="text-white-50 small mb-4">
                                Lupa kata sandi akun Anda? Masukkan email dan nomor HP/WhatsApp yang terdaftar untuk langsung membuat kata sandi baru.
                            </p>
                        </div>

                        <div class="d-flex flex-column gap-2 my-3">
                            <div class="feature-item d-flex align-items-center gap-3">
                                <div class="text-warning fs-5"><i class="bi bi-person-check"></i></div>
                                <div>
                                    <div class="fw-semibold small">1. Verifikasi Identitas</div>
                                    <div class="text-white-50" style="font-size: 0.76rem;">Cocokkan email & nomor HP terdaftar</div>
                                </div>
                            </div>
                            <div class="feature-item d-flex align-items-center gap-3">
                                <div class="text-warning fs-5"><i class="bi bi-key"></i></div>
                                <div>
                                    <div class="fw-semibold small">2. Buat Sandi Baru</div>
                                    <div class="text-white-50" style="font-size: 0.76rem;">Minimal 6 karakter dan langsung aktif</div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-top border-secondary border-opacity-25 small text-white-50">
                            &copy; {{ date('Y') }} Studio Musik Positif.
                        </div>
                    </div>

                    <!-- Panel Kanan: Form Reset Password -->
                    <div class="col-lg-7 p-4 p-md-5 d-flex flex-column justify-content-center bg-white">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-3 p-2 mb-3">
                                <i class="bi bi-key-fill fs-3"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">Lupa Kata Sandi</h3>
                            <p class="text-muted small mb-0">Lengkapi data di bawah ini untuk mengatur ulang kata sandi Anda</p>
                        </div>

                        <form action="{{ route('password.direct_reset') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold small text-dark">Alamat Email Terdaftar</label>
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
                                <label for="no_hp" class="form-label fw-semibold small text-dark">Nomor HP / WhatsApp Terdaftar</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-custom">
                                        <i class="bi bi-whatsapp"></i>
                                    </span>
                                    <input type="text"
                                           id="no_hp"
                                           name="no_hp"
                                           value="{{ old('no_hp') }}"
                                           placeholder="Contoh: 081234567890"
                                           class="form-control form-control-lg-custom @error('no_hp') is-invalid @enderror"
                                           required>
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-semibold small text-dark">Kata Sandi Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text input-group-text-custom">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password"
                                               id="password"
                                               name="password"
                                               placeholder="Minimal 6 karakter"
                                               class="form-control form-control-lg-custom @error('password') is-invalid @enderror"
                                               required>
                                        <button type="button" class="btn btn-outline-secondary border-start-0" style="border-color: #dee2e6;" onclick="toggleField('password', 'iconPass1')">
                                            <i class="bi bi-eye" id="iconPass1"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-semibold small text-dark">Konfirmasi Sandi Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text input-group-text-custom">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="password"
                                               id="password_confirmation"
                                               name="password_confirmation"
                                               placeholder="Ulangi sandi baru"
                                               class="form-control form-control-lg-custom"
                                               required>
                                        <button type="button" class="btn btn-outline-secondary border-start-0" style="border-color: #dee2e6;" onclick="toggleField('password_confirmation', 'iconPass2')">
                                            <i class="bi bi-eye" id="iconPass2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-reset-submit w-100 fw-bold">
                                <i class="bi bi-check2-circle me-2"></i>Simpan Kata Sandi Baru
                            </button>
                        </form>

                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="small text-muted mb-0">
                                Sudah ingat kata sandi Anda?
                                <a href="{{ route('login') }}" class="fw-bold text-dark text-decoration-none border-bottom border-warning border-2 pb-1 ms-1">
                                    Kembali ke Halaman Masuk
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
    function toggleField(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
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