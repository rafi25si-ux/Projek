<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['reservasi.user', 'reservasi.studio']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pembayarans = $query->latest()->paginate(15)->withQueryString();

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function verifikasi(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'aksi' => ['required', 'in:terima,tolak'],
            'alasan_penolakan' => ['required_if:aksi,tolak', 'nullable', 'string', 'max:500'],
        ]);

        if ($validated['aksi'] === 'terima') {
            $pembayaran->update([
                'status' => 'Disetujui',
                'alasan_penolakan' => null,
            ]);

            $pembayaran->reservasi->update([
                'status' => 'Dikonfirmasi',
            ]);

            return back()->with('success', 'Pembayaran diterima dan jadwal reservasi telah dikonfirmasi.');
        }

        $pembayaran->update([
            'status' => 'Ditolak',
            'alasan_penolakan' => $validated['alasan_penolakan'],
        ]);

        $pembayaran->reservasi->update([
            'status' => 'Menunggu pembayaran',
        ]);

        return back()->with('success', 'Pembayaran ditolak. Pelanggan diminta mengunggah ulang bukti pembayaran.');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        if ($pembayaran->bukti_path && Storage::disk('public')->exists($pembayaran->bukti_path)) {
            Storage::disk('public')->delete($pembayaran->bukti_path);
        }

        $pembayaran->delete();

        return back()->with('success', 'Data pembayaran berhasil dihapus.');
    }
}