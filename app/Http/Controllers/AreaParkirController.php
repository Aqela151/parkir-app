<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AreaParkir;

class AreaParkirController extends Controller
{
    public function index()
    {
        $areas = AreaParkir::all();
        return view('admin.area-parkir', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_area' => 'required|string',
            'lokasi' => 'nullable|string',
            'kapasitas' => 'required|integer|min:0',
            'terisi' => 'nullable|integer|min:0',
            'status' => 'required|in:aktif,penuh,maintenance',
        ]);

        AreaParkir::create($request->all());

        return back()->with('success', 'Area Parkir berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $area = AreaParkir::findOrFail($id);

        $request->validate([
            'nama_area' => 'required|string',
            'lokasi' => 'nullable|string',
            'kapasitas' => 'required|integer|min:0',
            'terisi' => 'nullable|integer|min:0',
            'status' => 'required|in:aktif,penuh,maintenance',
        ]);

        $area->update($request->all());

        return back()->with('success', 'Area Parkir berhasil diupdate.');
    }

    public function destroy($id)
    {
        $area = AreaParkir::findOrFail($id);
        $area->delete();

        return back()->with('success', 'Area Parkir berhasil dihapus.');
    }
}
