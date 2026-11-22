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
        $kodeInput = strtoupper(trim($request->input('kode_promo', $request->input('kode', ''))));

        if ($kodeInput === '') {
            return response()->json([
                'valid' => false,
                'status' => false,
                'message' => 'Silakan masukkan kode promo terlebih dahulu.',
            ], 422);
        }

        $promo = Promo::where('kode', $kodeInput)
            ->whereDate('berlaku_mulai', '<=', now())
            ->whereDate('berlaku_sampai', '>=', now())
            ->first();

        if (!$promo) {
            return response()->json([
                'valid' => false,
                'status' => false,
                'message' => 'Kode promo tidak ditemukan atau sudah kedaluwarsa.',
            ], 422);
        }

        $subtotal = (float) $request->input('subtotal', 0);
        $tipe = $promo->tipe_potongan ?? $promo->tipe_diskon ?? 'nominal';
        $nilai = (float) ($promo->nilai_potongan ?? $promo->nilai_diskon ?? 0);

        $diskon = in_array($tipe, ['persen', 'persentase'])
            ? ($subtotal * $nilai) / 100
            : $nilai;

        if ($subtotal > 0) {
            $diskon = min($diskon, $subtotal);
        }

        return response()->json([
            'valid' => true,
            'status' => true,
            'promo_id' => $promo->id,
            'kode' => $promo->kode,
            'tipe_potongan' => $tipe,
            'nilai_potongan' => $nilai,
            'diskon' => $diskon,
            'total_bayar' => max(0, $subtotal - $diskon),
            'message' => 'Kode promo berhasil diterapkan!',
        ]);
    }

    public function create(Request $request, Studio $studio)
    {
        if (method_exists(Reservasi::class, 'batalkanKedaluwarsa')) {
            Reservasi::batalkanKedaluwarsa();
        }

        $tanggal = $request->input('tanggal', now()->toDateString());

        $jadwalTerisi = Reservasi::where('studio_id', $studio->id)
            ->whereDate('tanggal', $tanggal)
            ->whereNotIn('status', ['Dibatalkan'])
            ->orderBy('jam_mulai')
            ->get();

        return view('pelanggan.booking', compact('studio', 'jadwalTerisi', 'tanggal'));
    }

    public function store(Request $request, Studio $studio)
    {
        if (method_exists(Reservasi::class, 'batalkanKedaluwarsa')) {
            Reservasi::batalkanKedaluwarsa();
        }

        $validated = $request->validate([
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai' => ['required'],
            'durasi_jam' => ['required', 'integer', 'min:1', 'max:12'],
            'kode_promo' => ['nullable', 'string'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $jamMulai = Carbon::parse($validated['tanggal'] . ' ' . $validated['jam_mulai']);
        $durasi = (int) $validated['durasi_jam'];
        $jamSelesai = $jamMulai->copy()->addHours($durasi);

        $bentrok = Reservasi::where('studio_id', $studio->id)
            ->whereDate('tanggal', $validated['tanggal'])
            ->whereNotIn('status', ['Dibatalkan'])
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query->where('jam_mulai', '<', $jamSelesai->format('H:i:s'))
                      ->where('jam_selesai', '>', $jamMulai->format('H:i:s'));
            })
            ->exists();

        if ($bentrok) {
            return back()->withInput()->with('error', 'Jadwal pada jam tersebut sudah terisi. Silakan pilih jam lain.');
        }

        $subtotal = $studio->harga_per_jam * $durasi;
        $diskon = 0;
        $promoId = null;

        if (!empty($validated['kode_promo'])) {
            $promo = Promo::where('kode', strtoupper(trim($validated['kode_promo'])))
                ->whereDate('berlaku_mulai', '<=', now())
                ->whereDate('berlaku_sampai', '>=', now())
                ->first();

            if ($promo) {
                $promoId = $promo->id;
                $tipe = $promo->tipe_potongan ?? $promo->tipe_diskon ?? 'nominal';
                $nilai = (float) ($promo->nilai_potongan ?? $promo->nilai_diskon ?? 0);

                $diskon = in_array($tipe, ['persen', 'persentase'])
                    ? ($subtotal * $nilai) / 100
                    : $nilai;

                $diskon = min($diskon, $subtotal);
            }
        }

        $totalBayar = max(0, $subtotal - $diskon);
        $kodeBooking = 'SMP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

        $reservasi = Reservasi::create([
            'kode' => $kodeBooking,
            'kode_booking' => $kodeBooking,
            'user_id' => Auth::id(),
            'studio_id' => $studio->id,
            'promo_id' => $promoId,
            'tanggal' => $validated['tanggal'],
            'jam_mulai' => $jamMulai->format('H:i:s'),
            'jam_selesai' => $jamSelesai->format('H:i:s'),
            'durasi_jam' => $durasi,
            'subtotal' => $subtotal,
            'diskon' => $diskon,
            'total_harga' => $totalBayar,
            'total_bayar' => $totalBayar,
            'catatan' => $validated['catatan'] ?? null,
            'status' => 'Menunggu pembayaran',
        ]);

        return redirect()->route('riwayat.detail', $reservasi)
            ->with('success', 'Reservasi berhasil dibuat! Silakan lakukan pembayaran dalam 5 menit dan unggah bukti transfer.');
    }

    public function riwayat()
    {
        if (method_exists(Reservasi::class, 'batalkanKedaluwarsa')) {
            Reservasi::batalkanKedaluwarsa();
        }

        $reservasis = Reservasi::with(['studio.fotos', 'pembayaran', 'ulasan'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pelanggan.riwayat', compact('reservasis'));
    }

    public function detail(Reservasi $reservasi)
    {
        abort_if($reservasi->user_id !== Auth::id(), 403);

        if (method_exists(Reservasi::class, 'batalkanKedaluwarsa')) {
            Reservasi::batalkanKedaluwarsa();
            $reservasi->refresh();
        }

        $reservasi->load(['studio.fotos', 'promo', 'pembayaran', 'ulasan']);

        return view('pelanggan.detail_reservasi', compact('reservasi'));
    }

    public function uploadBukti(Request $request, Reservasi $reservasi)
    {
        abort_if($reservasi->user_id !== Auth::id(), 403);

        if (method_exists(Reservasi::class, 'batalkanKedaluwarsa')) {
            Reservasi::batalkanKedaluwarsa();
            $reservasi->refresh();
        }

        if ($reservasi->status === 'Dibatalkan') {
            return back()->with('error', 'Batas waktu pembayaran 5 menit telah habis. Reservasi otomatis dibatalkan.');
        }

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
            return back()->with('error', 'Reservasi tidak dapat dibatalkan.');
        }

        $reservasi->update(['status' => 'Dibatalkan']);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }

    public function invoice(Reservasi $reservasi)
    {
        abort_if($reservasi->user_id !== Auth::id(), 403);
        abort_if(!in_array($reservasi->status, ['Dikonfirmasi', 'Selesai']), 403);

        $reservasi->load(['user', 'studio', 'promo', 'pembayaran']);

        return view('pelanggan.invoice', compact('reservasi'));
    }
}