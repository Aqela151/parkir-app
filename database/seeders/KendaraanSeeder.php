<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KendaraanSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kendaraans = [
            [
                'plat_nomor' => 'N 1234 AB',
                'jenis' => 'Motor',
                'warna' => 'Hitam',
                'nama_pemilik' => 'Aqela',
                'gambar' => null,
            ],
            [
                'plat_nomor' => 'N 5678 CD',
                'jenis' => 'Mobil',
                'warna' => 'Putih',
                'nama_pemilik' => 'Qela',
                'gambar' => null,
            ],
            [
                'plat_nomor' => 'N 9012 EF',
                'jenis' => 'Motor',
                'warna' => 'Merah',
                'nama_pemilik' => 'Qey',
                'gambar' => null,
            ],
            [
                'plat_nomor' => 'AG 5588 DE',
                'jenis' => 'Mobil',
                'warna' => 'Hitam',
                'nama_pemilik' => 'Nisa',
                'gambar' => null,
            ],
            [
                'plat_nomor' => 'L 9977 BY',
                'jenis' => 'Mobil',
                'warna' => 'Kuning',
                'nama_pemilik' => 'Niisaa',
                'gambar' => null,
            ],
        ];

        foreach ($kendaraans as $kendaraan) {
            Kendaraan::create($kendaraan);
        }
    }
}