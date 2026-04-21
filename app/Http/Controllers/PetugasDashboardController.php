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
        $user = Auth::user();
        $penempatan = $user->penempatan;
        
        // Get the assigned area for the petugas
        $assignedArea = null;
        if ($penempatan) {
            $assignedArea = AreaParkir::where('nama_area', $penempatan)->where('status', 'aktif')->first();
        }
        
        // Get latest transactions for today (all areas, not filtered by assigned area)
        $transaksiTerakhir = Transaksi::with(['kendaraan', 'area'])->whereDate('waktu_masuk', today())
            ->orderBy('waktu_masuk', 'desc')
            ->take(10)
            ->get()
            ->map(function ($t) {
                return (object) [
                    'id' => $t->id,
                    'no_plat' => $t->kendaraan->plat_nomor ?? 'N/A',
                    'jenis_kendaraan' => $t->kendaraan->jenis ?? 'N/A',
                    'waktu_masuk' => $t->waktu_masuk,
                    'status' => $t->status,
                ];
            });
        
        // Get area information for the logged-in petugas
        $namaArea = $penempatan ?? 'Area';
        
        // Get status area data based on real transactions from all active areas
        $statusArea = AreaParkir::where('status', 'aktif')
            ->get()
            ->map(function ($area) {
                $kapasitas = (int)($area->kapasitas ?? 0);
                $terisi = (int) Transaksi::where('area_id', $area->id)
                    ->where('status', 'parkir')
                    ->count();
                
                return [
                    'nama' => $area->nama_area ?? 'Area',
                    'kapasitas' => $kapasitas,
                    'terisi' => $terisi,
                    'alamat' => $area->lokasi ?? null,
                ];
            })
            ->values();
        
        // Stats for today, filtered by assigned area if exists
        $statsQuery = Transaksi::whereDate('waktu_masuk', today());
        if ($assignedArea) {
            $statsQuery->where('area_id', $assignedArea->id);
        }
        $kendaraanMasuk = (clone $statsQuery)->count();
        
        $keluarQuery = Transaksi::whereDate('waktu_keluar', today())->whereNotNull('waktu_keluar');
        if ($assignedArea) {
            $keluarQuery->where('area_id', $assignedArea->id);
        }
        $kendaraanKeluar = $keluarQuery->count();
        
        $parkirQuery = Transaksi::where('status', 'parkir');
        if ($assignedArea) {
            $parkirQuery->where('area_id', $assignedArea->id);
        }
        $kendaraanParkir = $parkirQuery->count();
        
        $jumlahTransaksiBaru = $transaksiTerakhir->where('status', 'parkir')->count();
        
        return view('petugas.dashboard', compact(
            'transaksiTerakhir', 
            'namaArea', 
            'statusArea', 
            'kendaraanMasuk',
            'kendaraanKeluar',
            'kendaraanParkir',
            'jumlahTransaksiBaru'
        ));
    }

    public function statusArea()
    {
        $statusArea = AreaParkir::where('status', 'aktif')->get()->map(function ($area) {
            $kapasitas = $area->kapasitas;
            $terisi = Transaksi::where('area_id', $area->id)
                ->where('status', 'parkir')
                ->count();

            return [
                'nama' => $area->nama_area,
                'alamat' => $area->lokasi,
                'kapasitas' => $kapasitas,
                'terisi' => $terisi,
            ];
        })->values();

        return view('petugas.status-area', compact('statusArea'));
    }

    public function getStatusArea()
    {
        $statusArea = AreaParkir::where('status', 'aktif')->get()->map(function ($area) {
            $kapasitas = $area->kapasitas;
            $terisi = Transaksi::where('area_id', $area->id)
                ->where('status', 'parkir')
                ->count();

            return [
                'nama' => $area->nama_area,
                'alamat' => $area->lokasi,
                'kapasitas' => $kapasitas,
                'terisi' => $terisi,
            ];
        })->values();

        return response()->json($statusArea);
    }
}
