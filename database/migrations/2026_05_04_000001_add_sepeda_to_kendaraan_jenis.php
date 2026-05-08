<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the jenis column to add 'Sepeda' to the enum
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->enum('jenis', ['Motor', 'Mobil', 'Bus/Truk', 'Sepeda'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->enum('jenis', ['Motor', 'Mobil', 'Bus/Truk'])->change();
        });
    }
};
