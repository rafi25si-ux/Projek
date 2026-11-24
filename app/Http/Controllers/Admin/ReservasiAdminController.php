<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class ReservasiAdminController extends Controller
{
    public function index(Request $request)
    {
        if (method_exists(Reservasi::class, 'batalkanKedaluwarsa')) {
            Reservasi::batalkanKedaluwarsa();
        }

        $query = Reservasi::with(['user', 'studio', 'promo', 'pembayaran']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('kode_booking', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

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

        return back()->with('success', 'Status reservasi ' . ($reservasi->kode_booking ?? $reservasi->kode) . ' diperbarui menjadi ' . $validated['status'] . '.');
    }

    public function destroy(Reservasi $reservasi)
    {
        if (!in_array($reservasi->status, ['Menunggu pembayaran', 'Dibatalkan'])) {
            return back()->with('error', 'Hanya reservasi berstatus Menunggu pembayaran atau Dibatalkan yang dapat dihapus.');
        }

        $kode = $reservasi->kode_booking ?? $reservasi->kode;
        $reservasi->delete();

        return back()->with('success', 'Reservasi ' . $kode . ' berhasil dihapus.');
    }
}