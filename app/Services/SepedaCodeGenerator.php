<?php

namespace App\Services;

use App\Models\Kendaraan;

class SepedaCodeGenerator
{
    /**
     * Prefix untuk kode sepeda
     */
    private const CODE_PREFIX = 'SPD';

    /**
     * Panjang nomor yang di-generate
     */
    private const CODE_LENGTH = 3;

    /**
     * Generates unique code for sepeda (bicycle)
     * Format: SPD001, SPD002, SPD003, etc.
     *
     * @return string Generated unique code
     */
    public function generateCode(): string
    {
        $lastCode = $this->getLastCode();
        $nextNumber = $this->extractNumber($lastCode) + 1;
        
        return $this->formatCode($nextNumber);
    }

    /**
     * Gets the last generated sepeda code from database
     *
     * @return string|null Last code or null if none exists
     */
    private function getLastCode(): ?string
    {
        $lastKendaraan = Kendaraan::where('jenis', 'Sepeda')
            ->where('plat_nomor', 'like', self::CODE_PREFIX . '%')
            ->orderByRaw("CAST(SUBSTRING(plat_nomor, " . (strlen(self::CODE_PREFIX) + 1) . ") AS UNSIGNED) DESC")
            ->first();

        return $lastKendaraan?->plat_nomor;
    }

    /**
     * Extracts numeric part from code
     * Example: SPD001 => 1, SPD042 => 42
     *
     * @param string|null $code
     * @return int Extracted number or 0 if none
     */
    private function extractNumber(?string $code): int
    {
        if (!$code) {
            return 0;
        }

        $number = substr($code, strlen(self::CODE_PREFIX));
        return (int) $number;
    }

    /**
     * Formats number to code
     * Example: 1 => SPD001, 42 => SPD042
     *
     * @param int $number
     * @return string Formatted code
     */
    private function formatCode(int $number): string
    {
        return self::CODE_PREFIX . str_pad($number, self::CODE_LENGTH, '0', STR_PAD_LEFT);
    }

    /**
     * Validates if a code is valid sepeda code format
     *
     * @param string $code
     * @return bool
     */
    public function isValidCode(string $code): bool
    {
        $pattern = '/^' . self::CODE_PREFIX . '\d{' . self::CODE_LENGTH . '}$/';
        return (bool) preg_match($pattern, $code);
    }

    /**
     * Checks if a code already exists in database
     *
     * @param string $code
     * @return bool
     */
    public function codeExists(string $code): bool
    {
        return Kendaraan::where('plat_nomor', $code)->exists();
    }
}
