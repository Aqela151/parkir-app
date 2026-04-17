<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AreaParkir extends Model
{
    protected $fillable = ['nama_area', 'lokasi', 'kapasitas_mobil', 'kapasitas_motor', 'kapasitas_bus', 'status'];
    
    protected $table = 'area_parkirs';

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'area_id');
    }
}
