<?php

namespace App\Services;

use App\Models\TarifParkir;
use Carbon\Carbon;
use Exception;

class TarifService
{
    // Night hours: 10 PM (22:00) to 6 AM (06:00)
    private const NIGHT_START_HOUR = 22;
    private const NIGHT_END_HOUR = 6;
    private const MINIMUM_CHARGE = 5000; // Rp 5000 minimum

    /**
     * Calculate parking tariff based on vehicle type, duration, and time
     *
     * @param string $jenisKendaraan (e.g., 'mobil', 'motor', 'bus')
     * @param int $durasiMenit Duration in minutes
     * @param Carbon $waktuMasuk Entry time
     * @param Carbon $waktuKeluar Exit time
     * @return float Calculated tariff in rupiah
     * @throws Exception
     */
    public function calculateTariff($jenisKendaraan, $durasiMenit, Carbon $waktuMasuk, Carbon $waktuKeluar)
    {
        // Get tariff from database
        $tarif = TarifParkir::where('jenis_kendaraan', strtolower($jenisKendaraan))
            ->where('status', 'aktif')
            ->first();

        if (!$tarif) {
            throw new Exception("Tarif untuk kendaraan jenis '{$jenisKendaraan}' tidak ditemukan.");
        }

        $tarifPerJam = (float) $tarif->tarif_per_jam;
        $tarifFlatMalam = (float) $tarif->tarif_flat_malam;

        // If duration less than 1 hour, charge minimum
        if ($durasiMenit < 60) {
            return max($this::MINIMUM_CHARGE, $tarifPerJam);
        }

        // Check if parking spans night hours
        $hasNightHours = $this->hasNightHours($waktuMasuk, $waktuKeluar);

        if ($hasNightHours) {
            // Use flat night rate
            $jamParkir = ceil($durasiMenit / 60);
            $totalTarif = $jamParkir * $tarifFlatMalam;
        } else {
            // Calculate by hourly rate
            $jamParkir = ceil($durasiMenit / 60);
            $totalTarif = $jamParkir * $tarifPerJam;
        }

        return max($this::MINIMUM_CHARGE, $totalTarif);
    }

    /**
     * Check if parking duration spans night hours (10 PM - 6 AM)
     *
     * @param Carbon $waktuMasuk
     * @param Carbon $waktuKeluar
     * @return bool
     */
    private function hasNightHours(Carbon $waktuMasuk, Carbon $waktuKeluar)
    {
        $startHour = $waktuMasuk->hour;
        $endHour = $waktuKeluar->hour;

        // If entry is at night (10 PM - 6 AM)
        if ($startHour >= $this::NIGHT_START_HOUR || $startHour < $this::NIGHT_END_HOUR) {
            return true;
        }

        // If exit is at night (10 PM - 6 AM)
        if ($endHour >= $this::NIGHT_START_HOUR || $endHour < $this::NIGHT_END_HOUR) {
            return true;
        }

        // If the parking crosses midnight
        if ($endHour < $startHour) {
            return true;
        }

        // Check if any hour between start and end is night hour
        $current = $waktuMasuk->copy()->startOfHour();
        $end = $waktuKeluar->copy()->startOfHour();

        while ($current <= $end) {
            $hour = $current->hour;
            if ($hour >= $this::NIGHT_START_HOUR || $hour < $this::NIGHT_END_HOUR) {
                return true;
            }
            $current->addHour();
        }

        return false;
    }

    /**
     * Get tariff for a specific vehicle type
     *
     * @param string $jenisKendaraan
     * @return TarifParkir|null
     */
    public function getTariffByType($jenisKendaraan)
    {
        return TarifParkir::where('jenis_kendaraan', strtolower($jenisKendaraan))
            ->where('status', 'aktif')
            ->first();
    }

    /**
     * Estimate tariff for information purposes
     *
     * @param string $jenisKendaraan
     * @param int $durasiMenit
     * @return array ['estimasi' => float, 'minimum' => float, 'berdasarkan_db' => bool]
     */
    public function estimateTariff($jenisKendaraan, $durasiMenit)
    {
        $tarif = $this->getTariffByType($jenisKendaraan);

        if (!$tarif) {
            return [
                'estimasi' => $this::MINIMUM_CHARGE,
                'minimum' => $this::MINIMUM_CHARGE,
                'berdasarkan_db' => false,
                'pesan' => "Tarif untuk '{$jenisKendaraan}' tidak ditemukan, menggunakan minimum charge.",
            ];
        }

        $jamParkir = max(1, ceil($durasiMenit / 60));
        $estimasi = $jamParkir * (float) $tarif->tarif_per_jam;

        return [
            'estimasi' => max($this::MINIMUM_CHARGE, $estimasi),
            'minimum' => $this::MINIMUM_CHARGE,
            'berdasarkan_db' => true,
            'tarif_per_jam' => (float) $tarif->tarif_per_jam,
            'tarif_flat_malam' => (float) $tarif->tarif_flat_malam,
        ];
    }
}
