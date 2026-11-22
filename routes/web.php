<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FasilitasController;
use App\Http\Controllers\Admin\PembayaranAdminController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\ReservasiAdminController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoritController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\UlasanController;
use Illuminate\Support\Facades\Route;

// Halaman Publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [HomeController::class, 'katalog'])->name('katalog');
Route::get('/studio/{studio}', [HomeController::class, 'detail'])->name('studio.detail');

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Halaman Pelanggan
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::post('/cek-promo', [ReservasiController::class, 'cekPromo'])->name('promo.cek');
    Route::get('/booking/{studio}', [ReservasiController::class, 'create'])->name('booking.create');
    Route::post('/booking/{studio}', [ReservasiController::class, 'store'])->name('booking.store');

    Route::get('/riwayat', [ReservasiController::class, 'riwayat'])->name('riwayat');
    Route::get('/riwayat/{reservasi}', [ReservasiController::class, 'detail'])->name('riwayat.detail');
    Route::post('/riwayat/{reservasi}/upload-bukti', [ReservasiController::class, 'uploadBukti'])->name('riwayat.upload_bukti');
    Route::post('/riwayat/{reservasi}/batal', [ReservasiController::class, 'batal'])->name('riwayat.batal');
    Route::get('/riwayat/{reservasi}/invoice', [ReservasiController::class, 'invoice'])->name('riwayat.invoice');

    Route::get('/favorit', [FavoritController::class, 'index'])->name('favorit.index');
    Route::post('/favorit/{studio}/toggle', [FavoritController::class, 'toggle'])->name('favorit.toggle');

    Route::post('/ulasan/{reservasi}', [UlasanController::class, 'store'])->name('ulasan.store');
});

// Halaman Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('studio', StudioController::class)->except(['show']);
    Route::delete('studio-foto/{foto}', [StudioController::class, 'hapusFoto'])->name('studio.hapus_foto');

    Route::resource('fasilitas', FasilitasController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('promo', PromoController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/reservasi', [ReservasiAdminController::class, 'index'])->name('reservasi.index');
    Route::patch('/reservasi/{reservasi}/status', [ReservasiAdminController::class, 'updateStatus'])->name('reservasi.status');
    Route::delete('/reservasi/{reservasi}', [ReservasiAdminController::class, 'destroy'])->name('reservasi.destroy');

    Route::get('/pembayaran', [PembayaranAdminController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran/{pembayaran}/verifikasi', [PembayaranAdminController::class, 'verifikasi'])->name('pembayaran.verifikasi');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle_status');
});