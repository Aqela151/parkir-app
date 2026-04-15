<?php

namespace Database\Seeders;

use App\Models\TarifParkir;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TarifParkirSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tarifs = [
            [
                'jenis_kendaraan' => 'Motor',
                'tarif_per_jam' => 2000,
                'tarif_flat_malam' => 15000,
                'status' => 'aktif',
            ],
            [
                'jenis_kendaraan' => 'Mobil',
                'tarif_per_jam' => 5000,
                'tarif_flat_malam' => 25000,
                'status' => 'aktif',
            ],
            [
                'jenis_kendaraan' => 'Bus/Truk',
                'tarif_per_jam' => 15000,
                'tarif_flat_malam' => 75000,
                'status' => 'aktif',
            ],
        ];

        foreach ($tarifs as $tarif) {
            TarifParkir::create($tarif);
        }
    }
}