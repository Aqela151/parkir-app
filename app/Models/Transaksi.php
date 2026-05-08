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
        'tarif_sementara' => 'decimal:2',
        'tarif_akhir' => 'decimal:2',
    ];

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(AreaParkir::class, 'area_id');
    }

    /**
     * Get the tariff record for this vehicle type
     */
    public function tarifParkir(): BelongsTo
    {
        return $this->belongsTo(TarifParkir::class, 'jenis_kendaraan', 'jenis_kendaraan')
            ->through($this->kendaraan());
    }

    /**
     * Scope: Get active parking transactions
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'parkir');
    }

    /**
     * Scope: Get completed transactions
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope: Get transactions for today
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('waktu_masuk', today());
    }
}
