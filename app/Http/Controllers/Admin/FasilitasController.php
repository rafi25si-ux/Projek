<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::withCount('studios')->latest()->paginate(15);
        return view('admin.fasilitas.index', compact('fasilitas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:fasilitas,nama'],
        ]);

        Fasilitas::create($validated);

        return back()->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function update(Request $request, Fasilitas $fasilita)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:fasilitas,nama,' . $fasilita->id],
        ]);

        $fasilita->update($validated);

        return back()->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Fasilitas $fasilita)
    {
        $fasilita->delete();
        return back()->with('success', 'Fasilitas berhasil dihapus.');
    }
}