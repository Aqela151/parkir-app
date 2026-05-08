<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AreaParkir extends Model
{
    protected $fillable = ['nama_area', 'lokasi', 'kapasitas', 'terisi', 'status'];
    
    protected $table = 'area_parkirs';

    protected $casts = [
        'kapasitas' => 'integer',
        'terisi' => 'integer',
    ];

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'area_id');
    }

    /**
     * Get current occupancy count from active transactions
     */
    public function getOccupancyCount()
    {
        return $this->transaksis()
            ->where('status', 'parkir')
            ->count();
    }

    /**
     * Check if area has available capacity
     */
    public function hasAvailableCapacity()
    {
        return $this->getOccupancyCount() < $this->kapasitas;
    }

    /**
     * Get available slots
     */
    public function getAvailableSlots()
    {
        return max(0, $this->kapasitas - $this->getOccupancyCount());
    }

    /**
     * Get occupancy percentage
     */
    public function getOccupancyPercentage()
    {
        if ($this->kapasitas === 0) {
            return 0;
        }
        return round(($this->getOccupancyCount() / $this->kapasitas) * 100, 2);
    }

    /**
     * Update status based on occupancy
     */
    public function updateStatusBasedOnOccupancy()
    {
        $currentOccupancy = $this->getOccupancyCount();
        
        if ($currentOccupancy >= $this->kapasitas) {
            $this->status = 'penuh';
        } else {
            $this->status = 'aktif';
        }
        
        $this->terisi = $currentOccupancy;
        $this->save();
    }

    /**
     * Scope: Get active areas
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
