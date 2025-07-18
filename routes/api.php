<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransController;

// =============================
// Webhook Midtrans (VA, QRIS, dsb.)
// =============================
Route::post('midtrans/callback', [MidtransController::class, 'handle']);
