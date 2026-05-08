<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AreaParkir;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

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
        
        // Log activity - capture lokasi yang dipilih user
        $user = Auth::user();
        LogAktivitas::create([
            'user_id' => $user->id,
            'aktivitas' => "Tambah area parkir {$request->nama_area} - Kapasitas {$request->kapasitas}",
            'lokasi' => $request->lokasi ?? '-',
        ]);

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
        
        // Log activity - capture lokasi yang dipilih user
        $user = Auth::user();
        LogAktivitas::create([
            'user_id' => $user->id,
            'aktivitas' => "Update area {$request->nama_area} - Kapasitas: {$request->kapasitas}, Status: {$request->status}",
            'lokasi' => $request->lokasi ?? '-',
        ]);

        return back()->with('success', 'Area Parkir berhasil diupdate.');
    }

    public function destroy($id)
    {
        $area = AreaParkir::findOrFail($id);
        $nama = $area->nama_area;
        $lokasi = $area->lokasi;
        $area->delete();
        
        // Log activity - capture lokasi asli dari area yang dihapus
        $user = Auth::user();
        LogAktivitas::create([
            'user_id' => $user->id,
            'aktivitas' => "Hapus area parkir {$nama}",
            'lokasi' => $lokasi ?? '-',
        ]);

        return back()->with('success', 'Area Parkir berhasil dihapus.');
    }

    public function apiGetAreas()
    {
        $areas = AreaParkir::all(['id', 'nama_area', 'lokasi', 'kapasitas', 'terisi', 'status']);
        return response()->json($areas);
    }
}
