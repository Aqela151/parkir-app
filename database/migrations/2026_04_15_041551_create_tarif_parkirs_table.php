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
        Schema::create('tarif_parkirs', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kendaraan');
            $table->decimal('tarif_per_jam', 10, 2);
            $table->decimal('tarif_flat_malam', 10, 2);
            $table->enum('status', ['aktif', 'draft'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_parkirs');
    }
};
