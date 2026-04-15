<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaParkir extends Model
{
    protected $fillable = ['nama_area', 'lokasi', 'kapasitas_mobil', 'kapasitas_motor', 'kapasitas_bus', 'status'];
    
    protected $table = 'area_parkirs';
}
