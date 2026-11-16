<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Reservasi;
use App\Models\Studio;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudio = Studio::count();$totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalReservasi = Reservasi::count();$totalPendapatan = Reservasi::whereIn('status', ['Dikonfirmasi', 'Selesai'])->sum('total_bayar');

        $perluVerifikasi = Pembayaran::where('status', 'Menunggu verifikasi')->count();$reservasiTerbaru = Reservasi::with(['user', 'studio', 'pembayaran'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudio',
            'totalPelanggan',
            'totalReservasi',
            'totalPendapatan',
            'perluVerifikasi',
            'reservasiTerbaru'
        ));
    }
}