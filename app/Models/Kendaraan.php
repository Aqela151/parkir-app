<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kendaraan extends Model
{
    protected $fillable = ['plat_nomor', 'jenis', 'warna', 'nama_pemilik', 'gambar'];
    
    protected $table = 'kendaraans';

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }
}
