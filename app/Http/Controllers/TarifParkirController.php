<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TarifParkir;

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

        return back()->with('success', 'Tarif Parkir berhasil diupdate.');
    }

    public function destroy($id)
    {
        $tarif = TarifParkir::findOrFail($id);
        $tarif->delete();

        return back()->with('success', 'Tarif Parkir berhasil dihapus.');
    }
}
