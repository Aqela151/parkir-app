<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Observers\KendaraanObserver;

class Kendaraan extends Model
{
    protected $fillable = ['plat_nomor', 'jenis', 'warna', 'nama_pemilik', 'gambar'];
    
    protected $table = 'kendaraans';

    /**
     * Boot the model and register observer
     */
    protected static function boot()
    {
        parent::boot();
        static::observe(KendaraanObserver::class);
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    /**
     * Check if this is a sepeda (bicycle)
     */
    public function isSepeda(): bool
    {
        return $this->jenis === 'Sepeda';
    }

    /**
     * Get display name for plat nomor
     * Shows "Sepeda" label for auto-generated codes
     */
    public function getPlatDisplayAttribute(): string
    {
        if ($this->isSepeda()) {
            return $this->plat_nomor . ' (Auto)';
        }
        return $this->plat_nomor;
    }
}
