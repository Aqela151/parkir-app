<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TarifParkir;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class TarifParkirController extends Controller
{
    public function index()
    {
        $tarifs = TarifParkir::all();
        return view('admin.tarif-parkir', compact('tarifs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kendaraan' => 'required|string',
            'tarif_per_jam' => 'required|numeric|min:0',
            'tarif_flat_malam' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,draft',
        ]);

        TarifParkir::create($request->all());
        
        // Log activity - untuk tarif, gunakan lokasi user yang login
        $user = Auth::user();
        LogAktivitas::create([
            'user_id' => $user->id,
            'aktivitas' => "Tambah tarif {$request->jenis_kendaraan} - Rp {$request->tarif_per_jam}/jam",
            'lokasi' => $user->penempatan ?? '-',
        ]);

        return back()->with('success', 'Tarif Parkir berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $tarif = TarifParkir::findOrFail($id);

        $request->validate([
            'jenis_kendaraan' => 'required|string',
            'tarif_per_jam' => 'required|numeric|min:0',
            'tarif_flat_malam' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,draft',
        ]);

        $tarif->update($request->all());
        
        // Log activity
        $user = Auth::user();
        LogAktivitas::create([
            'user_id' => $user->id,
            'aktivitas' => "Update tarif {$request->jenis_kendaraan} - Rp {$request->tarif_per_jam}/jam",
            'lokasi' => $user->penempatan ?? '-',
        ]);

        return back()->with('success', 'Tarif Parkir berhasil diupdate.');
    }

    public function destroy($id)
    {
        $tarif = TarifParkir::findOrFail($id);
        $jenis = $tarif->jenis_kendaraan;
        $tarif->delete();
        
        // Log activity
        $user = Auth::user();
        LogAktivitas::create([
            'user_id' => $user->id,
            'aktivitas' => "Hapus tarif {$jenis}",
            'lokasi' => $user->penempatan ?? '-',
        ]);

        return back()->with('success', 'Tarif Parkir berhasil dihapus.');
    }
}
