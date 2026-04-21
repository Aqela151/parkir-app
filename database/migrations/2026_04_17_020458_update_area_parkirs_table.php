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
        Schema::table('area_parkirs', function (Blueprint $table) {
            $table->integer('kapasitas')->default(0)->after('lokasi');
            $table->integer('terisi')->default(0)->after('kapasitas');
            $table->dropColumn(['kapasitas_mobil', 'kapasitas_motor', 'kapasitas_bus']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('area_parkirs', function (Blueprint $table) {
            $table->integer('kapasitas_mobil')->default(0)->after('lokasi');
            $table->integer('kapasitas_motor')->default(0)->after('kapasitas_mobil');
            $table->integer('kapasitas_bus')->default(0)->after('kapasitas_motor');
            $table->dropColumn(['kapasitas', 'terisi']);
        });
    }
};
