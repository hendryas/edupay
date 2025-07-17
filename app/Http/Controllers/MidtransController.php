<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function handle(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        Log::info('Webhook Midtrans VA:', $payload);

        $orderId = $payload['order_id'] ?? null;
        $status = $payload['transaction_status'] ?? null;

        if ($orderId && $status === 'settlement') {
            // TODO: Update database status ke 'lunas'
            Log::info("Transaksi $orderId berhasil, update status ke lunas");
        }

        return response()->json(['message' => 'OK'], 200);
    }
}
