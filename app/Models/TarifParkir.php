<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifParkir extends Model
{
    protected $fillable = ['jenis_kendaraan', 'tarif_per_jam', 'tarif_flat_malam', 'status'];
    
    protected $table = 'tarif_parkirs';
}
