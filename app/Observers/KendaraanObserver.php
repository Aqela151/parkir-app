<?php

namespace App\Observers;

use App\Models\Kendaraan;
use App\Services\SepedaCodeGenerator;

class KendaraanObserver
{
    protected SepedaCodeGenerator $codeGenerator;

    public function __construct(SepedaCodeGenerator $codeGenerator)
    {
        $this->codeGenerator = $codeGenerator;
    }

    /**
     * Handle the Kendaraan "creating" event.
     * Auto-generate code untuk sepeda
     */
    public function creating(Kendaraan $kendaraan): void
    {
        // Jika jenis kendaraan adalah Sepeda dan plat_nomor kosong
        if ($kendaraan->jenis === 'Sepeda' && empty($kendaraan->plat_nomor)) {
            $kendaraan->plat_nomor = $this->codeGenerator->generateCode();
        }
    }

    /**
     * Handle the Kendaraan "updating" event.
     * Jika user mengubah dari jenis lain ke Sepeda, generate code
     */
    public function updating(Kendaraan $kendaraan): void
    {
        // Jika jenis berubah menjadi Sepeda dan plat belum SPD format
        if ($kendaraan->jenis === 'Sepeda' && !$this->codeGenerator->isValidCode($kendaraan->plat_nomor)) {
            $kendaraan->plat_nomor = $this->codeGenerator->generateCode();
        }
    }
}
