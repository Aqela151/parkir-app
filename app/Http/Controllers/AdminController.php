<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\AreaParkir;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function logAktivitas()
    {
        $logs = LogAktivitas::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.log-aktivitas', compact('logs'));
    }

    /**
     * Get real-time status area for API
     * Used by dashboard to update status area display
     */
    public function getStatusArea()
    {
        $statusArea = AreaParkir::all()->map(function ($area) {
            $kapasitas = (int)($area->kapasitas ?? 0);
            $terisi = (int) Transaksi::where('area_id', $area->id)
                ->where('status', 'parkir')
                ->count();

            // Determine status
            $status = $area->status;
            if ($kapasitas > 0 && $terisi >= $kapasitas) {
                $status = 'penuh';
            }

            return [
                'id' => $area->id,
                'nama' => $area->nama_area,
                'alamat' => $area->lokasi,
                'kapasitas' => $kapasitas,
                'terisi' => $terisi,
                'status' => $status,
            ];
        })->values();

        return response()->json($statusArea);
    }
}
