<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::latest()->paginate(15);
        return view('admin.promo.index', compact('promos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:promos,kode'],
            'tipe_potongan' => ['required', 'in:persen,nominal'],
            'potongan' => ['required', 'numeric', 'min:1'],
            'berlaku_mulai' => ['required', 'date'],
            'berlaku_sampai' => ['required', 'date', 'after_or_equal:berlaku_mulai'],
        ]);

        $validated['kode'] = strtoupper(trim($validated['kode']));
        Promo::create($validated);

        return back()->with('success', 'Kode promo berhasil dibuat.');
    }

    public function update(Request $request, Promo $promo)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:promos,kode,' . $promo->id],
            'tipe_potongan' => ['required', 'in:persen,nominal'],
            'potongan' => ['required', 'numeric', 'min:1'],
            'berlaku_mulai' => ['required', 'date'],
            'berlaku_sampai' => ['required', 'date', 'after_or_equal:berlaku_mulai'],
        ]);

        $validated['kode'] = strtoupper(trim($validated['kode']));
        $promo->update($validated);

        return back()->with('success', 'Kode promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return back()->with('success', 'Kode promo berhasil dihapus.');
    }
}