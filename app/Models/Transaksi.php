<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $fillable = [
        'kendaraan_id',
        'area_id',
        'waktu_masuk',
        'waktu_keluar',
        'tarif_sementara',
        'tarif_akhir',
        'status',
        'durasi_menit',
    ];

    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(AreaParkir::class, 'area_id');
    }
}
