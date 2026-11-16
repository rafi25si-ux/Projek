<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class ReservasiAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservasi::with(['user', 'studio', 'promo', 'pembayaran']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $reservasis = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reservasi.index', compact('reservasis'));
    }

    public function updateStatus(Request $request, Reservasi $reservasi)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Menunggu pembayaran,Menunggu verifikasi,Dikonfirmasi,Selesai,Dibatalkan'],
        ]);

        $reservasi->update(['status' => $validated['status']]);

        return back()->with('success', 'Status reservasi ' . $reservasi->kode_booking . ' diperbarui menjadi ' . $validated['status'] . '.');
    }
}