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
                'kapasitas' => 550, // 200+300+50
                'terisi' => 0,
                'status' => 'aktif',
            ],
            [
                'nama_area' => 'Malang Town Square',
                'lokasi' => 'Jl. Veteran No.2, Lowokwaru',
                'kapasitas' => 660, // 250+350+60
                'terisi' => 0,
                'status' => 'aktif',
            ],
            [
                'nama_area' => 'Malang Plaza',
                'lokasi' => 'Jl. KH Agus Salim 18, Sukoharjo',
                'kapasitas' => 440, // 180+220+40
                'terisi' => 0,
                'status' => 'aktif',
            ],
            [
                'nama_area' => 'Alun-alun Merdeka Malang',
                'lokasi' => 'Jl. Merdeka Selatan, Klojen',
                'kapasitas' => 330, // 150+150+30
                'terisi' => 0,
                'status' => 'aktif',
            ],
        ];

        foreach ($areas as $area) {
            AreaParkir::create($area);
        }
    }
}