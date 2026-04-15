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
        Schema::create('area_parkirs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_area');
            $table->text('lokasi')->nullable();
            $table->integer('kapasitas_mobil')->default(0);
            $table->integer('kapasitas_motor')->default(0);
            $table->integer('kapasitas_bus')->default(0);
            $table->enum('status', ['aktif', 'penuh', 'maintenance'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_parkirs');
    }
};
