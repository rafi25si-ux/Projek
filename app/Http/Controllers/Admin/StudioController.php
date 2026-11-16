<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\Studio;
use App\Models\StudioFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudioController extends Controller
{
    public function index()
    {
        $studios = Studio::with(['fotos', 'fasilitas'])->latest()->paginate(10);
        return view('admin.studio.index', compact('studios'));
    }

    public function create()
    {
        $fasilitas = Fasilitas::orderBy('nama')->get();
        return view('admin.studio.create', compact('fasilitas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'kapasitas' => ['required', 'integer', 'min:1'],
            'harga_per_jam' => ['required', 'numeric', 'min:0'],
            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['exists:fasilitas,id'],
            'fotos' => ['nullable', 'array'],
            'fotos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $studio = Studio::create([
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'],
            'kapasitas' => $validated['kapasitas'],
            'harga_per_jam' => $validated['harga_per_jam'],
        ]);

        if (!empty($validated['fasilitas'])) {
            $studio->fasilitas()->sync($validated['fasilitas']);
        }

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $path = $file->store('studio-fotos', 'public');
                StudioFoto::create([
                    'studio_id' => $studio->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.studio.index')->with('success', 'Studio baru berhasil ditambahkan.');
    }

    public function edit(Studio $studio)
    {
        $studio->load(['fotos', 'fasilitas']);
        $fasilitas = Fasilitas::orderBy('nama')->get();
        return view('admin.studio.edit', compact('studio', 'fasilitas'));
    }

    public function update(Request $request, Studio $studio)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'kapasitas' => ['required', 'integer', 'min:1'],
            'harga_per_jam' => ['required', 'numeric', 'min:0'],
            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['exists:fasilitas,id'],
            'fotos' => ['nullable', 'array'],
            'fotos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $studio->update([
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'],
            'kapasitas' => $validated['kapasitas'],
            'harga_per_jam' => $validated['harga_per_jam'],
        ]);

        $studio->fasilitas()->sync($validated['fasilitas'] ?? []);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $path = $file->store('studio-fotos', 'public');
                StudioFoto::create([
                    'studio_id' => $studio->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.studio.index')->with('success', 'Data studio berhasil diperbarui.');
    }

    public function destroy(Studio $studio)
    {
        foreach ($studio->fotos as $foto) {
            Storage::disk('public')->delete($foto->path);
        }

        $studio->delete();

        return redirect()->route('admin.studio.index')->with('success', 'Studio berhasil dihapus.');
    }

    public function hapusFoto(StudioFoto $foto)
    {
        Storage::disk('public')->delete($foto->path);
        $foto->delete();

        return back()->with('success', 'Foto studio berhasil dihapus.');
    }
}