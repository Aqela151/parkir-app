<?php

namespace Database\Seeders;

use App\Models\AreaParkir;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaParkirSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            [
                'nama_area' => 'Mall Olympic Garden (MOG)',
                'lokasi' => 'Jl. Kawi No.24, Klojen',
                'kapasitas_mobil' => 200,
                'kapasitas_motor' => 300,
                'kapasitas_bus' => 50,
                'status' => 'aktif',
            ],
            [
                'nama_area' => 'Malang Town Square',
                'lokasi' => 'Jl. Veteran No.2, Lowokwaru',
                'kapasitas_mobil' => 250,
                'kapasitas_motor' => 350,
                'kapasitas_bus' => 60,
                'status' => 'aktif',
            ],
            [
                'nama_area' => 'Malang Plaza',
                'lokasi' => 'Jl. KH Agus Salim 18, Sukoharjo',
                'kapasitas_mobil' => 180,
                'kapasitas_motor' => 220,
                'kapasitas_bus' => 40,
                'status' => 'aktif',
            ],
            [
                'nama_area' => 'Alun-alun Merdeka Malang',
                'lokasi' => 'Jl. Merdeka Selatan, Klojen',
                'kapasitas_mobil' => 150,
                'kapasitas_motor' => 150,
                'kapasitas_bus' => 30,
                'status' => 'aktif',
            ],
        ];

        foreach ($areas as $area) {
            AreaParkir::create($area);
        }
    }
}