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
        Schema::create('pekerjaan_ortu', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pekerjaan', 10)->unique();
            $table->string('nama_pekerjaan', 100);
            $table->timestamps();
            $table->softDeletes(); // <--- Tambahkan ini
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pekerjaan_ortu');
    }
};
