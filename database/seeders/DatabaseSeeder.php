<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin/Petugas/Owner Users (only if they don't exist)
        if (!User::where('email', 'admin@parkir.com')->exists()) {
            User::create([
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@parkir.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'penempatan' => 'Pusat',
                'status' => 'aktif',
            ]);
        }

        if (!User::where('email', 'petugas@parkir.com')->exists()) {
            User::create([
                'name' => 'Petugas Parkir',
                'username' => 'petugas',
                'email' => 'petugas@parkir.com',
                'password' => Hash::make('petugas123'),
                'role' => 'petugas',
                'penempatan' => 'Area A',
                'status' => 'aktif',
            ]);
        }

        if (!User::where('email', 'owner@parkir.com')->exists()) {
            User::create([
                'name' => 'Pemilik Tempat Parkir',
                'username' => 'owner',
                'email' => 'owner@parkir.com',
                'password' => Hash::make('owner123'),
                'role' => 'owner',
                'penempatan' => 'Semua Area',
                'status' => 'aktif',
            ]);
        }

        // Seed sample data for testing
        $this->call([
            KendaraanSeeder::class,
            TarifParkirSeeder::class,
            AreaParkirSeeder::class,
        ]);
    }
}
