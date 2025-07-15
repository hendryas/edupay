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
        Schema::create('transaksi_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id')->nullable();
            $table->unsignedBigInteger('tagihan_id')->nullable();
            $table->date('tanggal_bayar');                           // Tanggal transaksi
            $table->decimal('jumlah_bayar', 12, 2);                  // Nominal yang dibayar
            $table->enum('metode', ['manual', 'midtrans', 'transfer']);         // Metode pembayaran
            $table->enum('status', ['pending', 'lunas', 'gagal'])->default('pending');
            $table->string('dibuat_oleh'); // Bisa simpan nama user/admin yang mencatat
            $table->string('bukti_transfer')->nullable();
            $table->timestamps();          // created_at & updated_at
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembayaran');
    }
};
