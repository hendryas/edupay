<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function handle(Request $request)
    {
        file_put_contents(storage_path('logs/midtrans_debug.txt'), now() . "\n" . $request->getContent() . "\n\n", FILE_APPEND);
        $payload = json_decode($request->getContent(), true);
        Log::info('Webhook Midtrans VA:', $payload);

        $orderId = $payload['order_id'] ?? null;
        $status = $payload['transaction_status'] ?? null;

        if ($orderId && $status === 'settlement') {
            // TODO: Update database status ke 'lunas'
            // Log::info("Transaksi $orderId berhasil, update status ke lunas");
            // $exploded = explode('-', $orderId); // TAGIHAN-15-1721212000
            // $tagihanId = $exploded[1] ?? null;
            $tagihanId = $orderId;

            if ($tagihanId) {
                $tagihan = Tagihan::find($tagihanId);
                if ($tagihan) {
                    $tagihan->status_pembayaran = 'lunas';
                    $tagihan->save();
                    Log::info("Tagihan ID $tagihanId diupdate ke lunas");
                }
            }
        }

        return response()->json(['message' => 'OK'], 200);
    }
}
