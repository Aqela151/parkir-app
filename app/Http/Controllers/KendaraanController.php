<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::all();
        return view('admin.data-kendaraan', compact('kendaraans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|unique:kendaraans',
            'jenis' => 'required|in:Motor,Mobil,Bus/Truk',
            'warna' => 'required|string',
            'nama_pemilik' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $path = $file->store('kendaraan', 'public');
            $data['gambar'] = $path;
        }

        Kendaraan::create($data);

        return back()->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $request->validate([
            'plat_nomor' => 'required|string|unique:kendaraans,plat_nomor,' . $id,
            'jenis' => 'required|in:Motor,Mobil,Bus/Truk',
            'warna' => 'required|string',
            'nama_pemilik' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $path = $file->store('kendaraan', 'public');
            $data['gambar'] = $path;
        }

        $kendaraan->update($data);

        return back()->with('success', 'Kendaraan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->delete();

        return back()->with('success', 'Kendaraan berhasil dihapus.');
    }
}
