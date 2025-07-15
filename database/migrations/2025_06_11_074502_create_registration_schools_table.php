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
        Schema::create('registration_schools', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->unsignedBigInteger('orang_tua_id')->nullable();
            $table->string('wali_nama');
            $table->string('wali_hp');
            $table->text('wali_alamat');
            $table->string('siswa_nama');
            $table->string('siswa_nisn')->nullable();
            $table->string('siswa_tempat_lahir')->nullable();
            $table->date('siswa_tanggal_lahir')->nullable();
            $table->string('siswa_jenis_kelamin');
            $table->string('siswa_jurusan');
            $table->string('foto_siswa');
            $table->string('akta_kelahiran');
            $table->string('kartu_keluarga');
            $table->string('ijazah_terakhir')->nullable();
            $table->dateTime('tanggal_pendaftaran')->nullable();
            $table->string('status_pendaftaran')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_schools');
    }
};
