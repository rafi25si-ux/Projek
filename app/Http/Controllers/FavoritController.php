<?php

namespace App\Http\Controllers;

use App\Models\Favorit;
use App\Models\Studio;
use Illuminate\Support\Facades\Auth;

class FavoritController extends Controller
{
    public function index()
    {
        $favorits = Favorit::with(['studio.fotos', 'studio.fasilitas', 'studio.ulasans'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pelanggan.favorit', compact('favorits'));
    }

    public function toggle(Studio $studio)
    {
        $existing = Favorit::where('user_id', Auth::id())
            ->where('studio_id', $studio->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Studio dihapus dari daftar favorit.');
        }

        Favorit::create([
            'user_id' => Auth::id(),
            'studio_id' => $studio->id,
        ]);

        return back()->with('success', 'Studio ditambahkan ke daftar favorit.');
    }
}