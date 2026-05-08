<?php

namespace App\Services;

use App\Models\Kendaraan;
use App\Models\AreaParkir;
use App\Models\Transaksi;
use Carbon\Carbon;
use Exception;

class TransaksiService
{
    /**
     * Validate if vehicle can enter parking area
     *
     * @param int $kendaraanId
     * @param int $areaId
     * @return array ['success' => bool, 'message' => string]
     */
    public function validateVehicleEntry($kendaraanId, $areaId)
    {
        $kendaraan = Kendaraan::findOrFail($kendaraanId);
        $area = AreaParkir::findOrFail($areaId);

        // Check 1: Is vehicle already parked?
        $parkingRecord = Transaksi::where('kendaraan_id', $kendaraanId)
            ->where('status', 'parkir')
            ->first();

        if ($parkingRecord) {
            return [
                'success' => false,
                'message' => "Kendaraan {$kendaraan->plat_nomor} sudah terdaftar sedang parkir di {$parkingRecord->area->nama_area}. Harap dikeluarkan terlebih dahulu.",
            ];
        }

        // Check 2: Is area at capacity?
        $currentOccupancy = Transaksi::where('area_id', $areaId)
            ->where('status', 'parkir')
            ->count();

        if ($currentOccupancy >= $area->kapasitas) {
            return [
                'success' => false,
                'message' => "Area parkir {$area->nama_area} sudah penuh ({$currentOccupancy}/{$area->kapasitas}).",
            ];
        }

        // Check 3: Is area operational?
        if ($area->status !== 'aktif') {
            return [
                'success' => false,
                'message' => "Area parkir {$area->nama_area} tidak aktif. Status: {$area->status}",
            ];
        }

        return [
            'success' => true,
            'message' => 'Validasi berhasil',
        ];
    }

    /**
     * Record vehicle entry
     *
     * @param int $kendaraanId
     * @param int $areaId
     * @return Transaksi
     * @throws Exception
     */
    public function recordEntry($kendaraanId, $areaId)
    {
        $validation = $this->validateVehicleEntry($kendaraanId, $areaId);
        
        if (!$validation['success']) {
            throw new Exception($validation['message']);
        }

        $transaksi = Transaksi::create([
            'kendaraan_id' => $kendaraanId,
            'area_id' => $areaId,
            'waktu_masuk' => Carbon::now('Asia/Jakarta'),
            'status' => 'parkir',
            'tarif_sementara' => 0,
        ]);

        return $transaksi;
    }

    /**
     * Record vehicle exit and calculate final tariff
     *
     * @param int $transaksiId
     * @param TarifService $tarifService
     * @return Transaksi
     * @throws Exception
     */
    public function recordExit($transaksiId, TarifService $tarifService)
    {
        $transaksi = Transaksi::with(['kendaraan', 'area'])->findOrFail($transaksiId);

        if ($transaksi->status !== 'parkir') {
            throw new Exception('Transaksi ini sudah selesai atau tidak valid.');
        }

        $waktuKeluar = Carbon::now('Asia/Jakarta');
        $durasiMenit = $transaksi->waktu_masuk->diffInMinutes($waktuKeluar);

        // Calculate tariff from database
        $tarifAkhir = $tarifService->calculateTariff(
            $transaksi->kendaraan->jenis,
            $durasiMenit,
            $transaksi->waktu_masuk,
            $waktuKeluar
        );

        $transaksi->update([
            'waktu_keluar' => $waktuKeluar,
            'durasi_menit' => $durasiMenit,
            'tarif_akhir' => $tarifAkhir,
            'status' => 'selesai',
        ]);

        return $transaksi;
    }
}
