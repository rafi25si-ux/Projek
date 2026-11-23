@extends('layouts.app')

@section('title', 'Daftar Akun - Studio Musik Positif')

@section('content')
<style>
    .register-wrapper {
        min-height: 78vh;
        display: flex;
        align-items: center;
    }
    .register-card {
        border-radius: 1.25rem;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
        background: #ffffff;
    }
    .register-brand-panel {
        background: radial-gradient(circle at top right, #2c2514 0%, #16181d 55%, #0d0e12 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .register-brand-panel::before {
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
        transition: transform 0.25s ease, border-color 0.25s ease;
    }
    .feature-item:hover {
        transform: translateX(4px);
        border-color: rgba(255, 193, 7, 0.3);
    }
    .form-control-lg-custom {
        padding: 0.72rem 0.95rem;
        font-size: 0.94rem;
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
    .btn-register-submit {
        background: linear-gradient(135deg, #1a1d24 0%, #0f1115 100%);
        border: none;
        color: #ffc107;
        padding: 0.82rem;
        border-radius: 0.65rem;
        transition: all 0.25s ease;
    }
    .btn-register-submit:hover {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #111317;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(255, 193, 7, 0.3);
    }
</style>

<div class="container register-wrapper my-4">
    <div class="row justify-content-center w-100 mx-0">
        <div class="col-xl-10 col-lg-11 px-0">
            <div class="card border-0 register-card">
                <div class="row g-0">
                    <!-- Panel Kiri: Visual & Benefit Bergabung -->
                    <div class="col-lg-5 register-brand-panel p-4 p-md-5 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge rounded-pill brand-badge px-3 py-2 mb-4">
                                <i class="bi bi-stars me-1"></i> KEANGGOTAAN GRATIS
                            </span>
                            <h2 class="fw-bold h2 mb-3" style="line-height: 1.25;">
                                Mulai Sesi Latihan & Rekamanmu Hari Ini.
                            </h2>
                            <p class="text-white-50 small mb-4">
                                Daftarkan akun pelanggan dalam hitungan detik dan nikmati kemudahan reservasi studio musik kapan saja.
                            </p>
                        </div>

                        <div class="d-flex flex-column gap-2 my-3">
                            <div class="feature-item d-flex align-items-center gap-3">
                                <div class="text-warning fs-4"><i class="bi bi-lightning-charge"></i></div>
                                <div>
                                    <div class="fw-semibold small">Booking Online 24/7</div>
                                    <div class="text-white-50" style="font-size: 0.76rem;">Pilih jam latihan langsung dari rumah</div>
                                </div>
                            </div>
                            <div class="feature-item d-flex align-items-center gap-3">
                                <div class="text-warning fs-4"><i class="bi bi-ticket-perforated"></i></div>
                                <div>
                                    <div class="fw-semibold small">Akses Promo & Diskon Khusus</div>
                                    <div class="text-white-50" style="font-size: 0.76rem;">Gunakan kode voucher saat reservasi</div>
                                </div>
                            </div>
                            <div class="feature-item d-flex align-items-center gap-3">
                                <div class="text-warning fs-4"><i class="bi bi-heart"></i></div>
                                <div>
                                    <div class="fw-semibold small">Simpan Studio Favorit</div>
                                    <div class="text-white-50" style="font-size: 0.76rem;">Tandai ruangan andalan band kamu</div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-top border-secondary border-opacity-25 small text-white-50">
                            &copy; {{ date('Y') }} Studio Musik Positif. All rights reserved.
                        </div>
                    </div>

                    <!-- Panel Kanan: Form Registrasi -->
                    <div class="col-lg-7 p-4 p-md-5 d-flex flex-column justify-content-center bg-white">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-3 p-2 mb-3">
                                <i class="bi bi-person-plus-fill fs-3"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">Buat Akun Pelanggan</h3>
                            <p class="text-muted small mb-0">Daftar gratis dan mulai booking studio favoritmu</p>
                        </div>

                        <form action="{{ route('register') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold small text-dark">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-custom">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           placeholder="Masukkan nama lengkap"
                                           class="form-control form-control-lg-custom @error('name') is-invalid @enderror"
                                           required
                                           autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
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
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="no_hp" class="form-label fw-semibold small text-dark">Nomor HP / WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text input-group-text-custom">
                                            <i class="bi bi-whatsapp"></i>
                                        </span>
                                        <input type="text"
                                               id="no_hp"
                                               name="no_hp"
                                               value="{{ old('no_hp') }}"
                                               placeholder="081234567890"
                                               class="form-control form-control-lg-custom @error('no_hp') is-invalid @enderror"
                                               required>
                                        @error('no_hp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-semibold small text-dark">Kata Sandi</label>
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
                                        <button type="button" class="btn btn-outline-secondary border-start-0" style="border-color: #dee2e6;" onclick="toggleRegisterPass('password', 'iconRegPass1')" title="Lihat/Sembunyikan Kata Sandi">
                                            <i class="bi bi-eye" id="iconRegPass1"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-semibold small text-dark">Konfirmasi Kata Sandi</label>
                                    <div class="input-group">
                                        <span class="input-group-text input-group-text-custom">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="password"
                                               id="password_confirmation"
                                               name="password_confirmation"
                                               placeholder="Ulangi kata sandi"
                                               class="form-control form-control-lg-custom"
                                               required>
                                        <button type="button" class="btn btn-outline-secondary border-start-0" style="border-color: #dee2e6;" onclick="toggleRegisterPass('password_confirmation', 'iconRegPass2')" title="Lihat/Sembunyikan Konfirmasi Sandi">
                                            <i class="bi bi-eye" id="iconRegPass2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-register-submit w-100 fw-bold">
                                <i class="bi bi-person-check me-2"></i>Daftar Sekarang
                            </button>
                        </form>

                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="small text-muted mb-0">
                                Sudah punya akun Studio Musik Positif?
                                <a href="{{ route('login') }}" class="fw-bold text-dark text-decoration-none border-bottom border-warning border-2 pb-1 ms-1">
                                    Masuk di sini
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
    function toggleRegisterPass(inputId, iconId) {
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