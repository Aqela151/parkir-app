<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TarifParkir extends Model
{
    protected $fillable = ['jenis_kendaraan', 'tarif_per_jam', 'tarif_flat_malam', 'status'];
    
    protected $table = 'tarif_parkirs';

    protected $casts = [
        'tarif_per_jam' => 'decimal:2',
        'tarif_flat_malam' => 'decimal:2',
    ];

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'jenis_kendaraan', 'jenis_kendaraan');
    }

    /**
     * Scope: Get active tariffs
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope: Get by vehicle type
     */
    public function scopeByType($query, $jenisKendaraan)
    {
        return $query->where('jenis_kendaraan', strtolower($jenisKendaraan));
    }

    /**
     * Format tariff for display
     */
    public function formatTarifPerJam()
    {
        return 'Rp ' . number_format($this->tarif_per_jam, 0, ',', '.');
    }

    public function formatTarifMalam()
    {
        return 'Rp ' . number_format($this->tarif_flat_malam, 0, ',', '.');
    }
}
