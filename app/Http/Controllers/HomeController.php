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
        $query = Studio::with(['fotos', 'fasilitas', 'ulasans']);

        if ($request->filled('cari')) {
            $query->where('nama', 'like', '%' . $request->cari . '%');
        }

        if ($request->filled('kapasitas')) {
            $query->where('kapasitas', '>=', (int) $request->kapasitas);
        }

        if ($request->filled('harga_max')) {
            $query->where('harga_per_jam', '<=', (int) $request->harga_max);
        }

        $studios = $query->paginate(9)->withQueryString();

        return view('public.katalog', compact('studios'));
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