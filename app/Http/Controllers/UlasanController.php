<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    public function store(Request $request, Reservasi $reservasi)
    {
        abort_if($reservasi->user_id !== Auth::id(), 403);

        if ($reservasi->status !== 'Selesai') {
            return back()->with('error', 'Ulasan hanya dapat diberikan setelah sesi reservasi selesai.');
        }

        if ($reservasi->ulasan) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk reservasi ini.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'komentar' => ['required', 'string', 'max:1000'],
        ]);

        Ulasan::create([
            'user_id' => Auth::id(),
            'studio_id' => $reservasi->studio_id,
            'reservasi_id' => $reservasi->id,
            'rating' => $validated['rating'],
            'komentar' => $validated['komentar'],
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda telah berhasil dikirim.');
    }
}