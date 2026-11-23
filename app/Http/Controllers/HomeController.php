<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Reservasi;
use App\Models\Studio;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $studios = Studio::with(['fotos', 'fasilitas', 'ulasans'])
            ->latest()
            ->take(6)
            ->get();

        $promos = Promo::whereDate('berlaku_mulai', '<=', now())
            ->whereDate('berlaku_sampai', '>=', now())
            ->get();

        return view('public.home', compact('studios', 'promos'));
    }

    public function katalog(Request $request)
    {
        $daftarStudio = Studio::orderBy('nama')->get();
        $query = Studio::with(['fotos', 'fasilitas', 'ulasans']);

        // 1. Pencarian Pintar (Nama Studio, Deskripsi, atau Fasilitas/Alat)
        if ($request->filled('cari')) {
            $keyword = trim($request->cari);
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', '%' . $keyword . '%')
                  ->orWhere('deskripsi', 'like', '%' . $keyword . '%')
                  ->orWhereHas('fasilitas', function ($qf) use ($keyword) {
                      $qf->where('nama_fasilitas', 'like', '%' . $keyword . '%');
                  });
            });
        }

        // 2. Filter Kapasitas Berdasarkan Skala Band
        if ($request->filled('kapasitas')) {
            match ($request->kapasitas) {
                'kecil'  => $query->where('kapasitas', '<=', 4),
                'sedang' => $query->whereBetween('kapasitas', [5, 7]),
                'besar'  => $query->where('kapasitas', '>=', 8),
                default  => is_numeric($request->kapasitas)
                    ? $query->where('kapasitas', '>=', (int) $request->kapasitas)
                    : null,
            };
        }

        // 3. Filter Budget / Tarif per Jam
        if ($request->filled('harga_max')) {
            $query->where('harga_per_jam', '<=', (int) $request->harga_max);
        }

        $studios = $query->orderBy('nama')->paginate(6)->withQueryString();

        return view('public.katalog', compact('studios', 'daftarStudio'));
    }
    public function detail(Request $request, Studio $studio)
    {
        $studio->load(['fotos', 'fasilitas', 'ulasans.user']);

        $tanggal = $request->input('tanggal', now()->toDateString());

        $jadwalTerisi = Reservasi::where('studio_id', $studio->id)
            ->whereDate('tanggal', $tanggal)
            ->whereNotIn('status', ['Dibatalkan'])
            ->orderBy('jam_mulai')
            ->get();

        return view('public.detail', compact('studio', 'jadwalTerisi', 'tanggal'));
    }
}