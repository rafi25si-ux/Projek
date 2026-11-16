<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Promo;
use App\Models\Reservasi;
use App\Models\Studio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReservasiController extends Controller
{
    public function cekPromo(Request $request)
    {
        $request->validate([
            'kode' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $promo = Promo::where('kode', strtoupper(trim($request->kode)))
            ->whereDate('berlaku_mulai', '<=', now())
            ->whereDate('berlaku_sampai', '>=', now())
            ->first();

        if (!$promo) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode promo tidak ditemukan atau sudah kedaluwarsa.',
            ], 422);
        }

        $diskon = $promo->tipe_potongan === 'persen'
            ? ($request->subtotal * $promo->potongan / 100)
            : $promo->potongan;

        $diskon = min($diskon, $request->subtotal);

        return response()->json([
            'valid' => true,
            'promo_id' => $promo->id,
            'kode' => $promo->kode,
            'diskon' => $diskon,
            'total_akhir' => $request->subtotal - $diskon,
            'message' => 'Kode promo berhasil diterapkan!',
        ]);
    }

    public function create(Studio $studio)
    {
        $studio->load(['fotos', 'fasilitas']);
        return view('pelanggan.booking', compact('studio'));
    }

    public function store(Request $request, Studio $studio)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'durasi_jam' => ['required', 'integer', 'min:1', 'max:12'],
            'kode_promo' => ['nullable', 'string'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $mulai = Carbon::createFromFormat('H:i', $validated['jam_mulai']);
        $selesai = (clone $mulai)->addHours((int) $validated['durasi_jam']);

        $jamMulaiStr = $mulai->format('H:i:s');
        $jamSelesaiStr = $selesai->format('H:i:s');

        $bentrok = Reservasi::where('studio_id', $studio->id)
            ->whereDate('tanggal', $validated['tanggal'])
            ->whereNotIn('status', ['Dibatalkan'])
            ->where(function ($q) use ($jamMulaiStr, $jamSelesaiStr) {
                $q->where('jam_mulai', '<', $jamSelesaiStr)
                  ->where('jam_selesai', '>', $jamMulaiStr);
            })
            ->exists();

        if ($bentrok) {
            return back()->withInput()->with('error', 'Jadwal yang Anda pilih bentrok dengan reservasi lain. Silakan pilih jam lain.');
        }

        $subtotal = $studio->harga_per_jam * (int) $validated['durasi_jam'];
        $diskon = 0;
        $promoId = null;

        if (!empty($validated['kode_promo'])) {
            $promo = Promo::where('kode', strtoupper(trim($validated['kode_promo'])))
                ->whereDate('berlaku_mulai', '<=', now())
                ->whereDate('berlaku_sampai', '>=', now())
                ->first();

            if ($promo) {
                $promoId = $promo->id;
                $diskon = $promo->tipe_potongan === 'persen'
                    ? ($subtotal * $promo->potongan / 100)
                    : $promo->potongan;
                $diskon = min($diskon, $subtotal);
            }
        }

        $reservasi = Reservasi::create([
            'kode_booking' => 'SMP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
            'user_id' => Auth::id(),
            'studio_id' => $studio->id,
            'promo_id' => $promoId,
            'tanggal' => $validated['tanggal'],
            'jam_mulai' => $jamMulaiStr,
            'jam_selesai' => $jamSelesaiStr,
            'durasi_jam' => $validated['durasi_jam'],
            'subtotal' => $subtotal,
            'diskon' => $diskon,
            'total_bayar' => $subtotal - $diskon,
            'status' => 'Menunggu pembayaran',
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('riwayat.detail', $reservasi)
            ->with('success', 'Reservasi berhasil dibuat! Silakan lakukan pembayaran dan unggah bukti transfer.');
    }

    public function riwayat()
    {
        $reservasis = Reservasi::with(['studio.fotos', 'pembayaran', 'ulasan'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pelanggan.riwayat', compact('reservasis'));
    }

    public function detail(Reservasi $reservasi)
    {
        abort_if($reservasi->user_id !== Auth::id(), 403);
        $reservasi->load(['studio.fotos', 'promo', 'pembayaran', 'ulasan']);

        return view('pelanggan.detail_reservasi', compact('reservasi'));
    }

    public function uploadBukti(Request $request, Reservasi $reservasi)
    {
        abort_if($reservasi->user_id !== Auth::id(), 403);

        $request->validate([
            'metode_pembayaran' => ['required', 'string', 'max:50'],
            'bukti' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $path = $request->file('bukti')->store('bukti-pembayaran', 'public');

        Pembayaran::create([
            'reservasi_id' => $reservasi->id,
            'metode_pembayaran' => $request->metode_pembayaran,
            'jumlah' => $reservasi->total_bayar,
            'bukti_path' => $path,
            'status' => 'Menunggu verifikasi',
        ]);

        $reservasi->update(['status' => 'Menunggu verifikasi']);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi Admin.');
    }

    public function batal(Reservasi $reservasi)
    {
        abort_if($reservasi->user_id !== Auth::id(), 403);

        if (!in_array($reservasi->status, ['Menunggu pembayaran', 'Menunggu verifikasi'])) {
            return back()->with('error', 'Pesanan yang sudah dikonfirmasi atau selesai tidak dapat dibatalkan.');
        }

        $reservasi->update(['status' => 'Dibatalkan']);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }

    public function invoice(Reservasi $reservasi)
    {
        abort_if($reservasi->user_id !== Auth::id(), 403);
        $reservasi->load(['user', 'studio', 'promo', 'pembayaran']);

        return view('pelanggan.invoice', compact('reservasi'));
    }
}