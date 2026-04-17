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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraans')->onDelete('cascade');
            $table->foreignId('area_id')->constrained('area_parkirs')->onDelete('cascade');
            $table->dateTime('waktu_masuk');
            $table->dateTime('waktu_keluar')->nullable();
            $table->integer('durasi_menit')->nullable();
            $table->decimal('tarif_sementara', 10, 2)->default(0);
            $table->decimal('tarif_akhir', 10, 2)->nullable();
            $table->enum('status', ['parkir', 'selesai'])->default('parkir');
            $table->timestamps();
            
            $table->index('status');
            $table->index('waktu_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
