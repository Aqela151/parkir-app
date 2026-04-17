<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasDashboardController extends Controller
{
    public function index()
    {
        // Get latest transactions (placeholder - adjust based on your transaction model)
        $transaksiTerakhir = collect([]);
        
        // Get area information for the logged-in petugas
        $namaArea = Auth::user()->penempatan ?? 'Area';
        
        // Get status area data (placeholder)
        $statusArea = collect([
            [
                'nama' => 'Area A',
                'kapasitas' => 100,
                'terisi' => 45,
            ],
            [
                'nama' => 'Area B', 
                'kapasitas' => 80,
                'terisi' => 65,
            ],
            [
                'nama' => 'Area C',
                'kapasitas' => 120,
                'terisi' => 30,
            ],
        ]);
        
        return view('petugas.dashboard', compact('transaksiTerakhir', 'namaArea', 'statusArea'));
    }

    public function statusArea()
    {
        $statusArea = AreaParkir::where('status', 'aktif')->get()->map(function ($area) {
            $kapasitas = $area->kapasitas_mobil + $area->kapasitas_motor + $area->kapasitas_bus;
            $terisi = Transaksi::where('area_id', $area->id)
                ->where('status', 'parkir')
                ->count();

            return [
                'nama' => $area->nama_area,
                'alamat' => $area->lokasi,
                'kapasitas' => $kapasitas,
                'terisi' => $terisi,
            ];
        });

        return view('petugas.status-area', compact('statusArea'));
    }
}
