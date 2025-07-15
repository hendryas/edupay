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
        Schema::create('orang_tua', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('siswa_id')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('hubungan_dengan_siswa')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orang_tua');
    }
};
