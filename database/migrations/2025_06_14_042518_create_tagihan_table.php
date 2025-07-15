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
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
            $table->integer('siswa_id')->nullable();
            $table->integer('biling_type_id')->nullable();
            $table->string('nama_tagihan');      // Contoh: SPP Januari, Kegiatan Semester, dll.
            $table->decimal('nominal', 12, 2);   // Jumlah tagihan
            $table->string('periode');           // Misal: Januari 2025, Semester Ganjil 2024, dll.
            $table->text('deskripsi')->nullable(); // Opsional keterangan tambahan
            $table->string('status_pembayaran')->nullable();
            $table->timestamps();                // created_at, updated_at
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
