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
        Schema::create('midtrans_payment', function (Blueprint $table) {
            $table->id();
            $table->integer('transaksi_id')->nullable();
            $table->decimal('order_amount', 12, 2);
            $table->string('payment_type');
            $table->dateTime('transaction_date');
            $table->string('transaction_status');
            $table->string('va_number');
            $table->string('snap_token');
            $table->string('midtrans_response');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('midtrans_payment');
    }
};
