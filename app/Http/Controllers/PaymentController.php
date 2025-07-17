<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Tagihan;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function getSnapToken(Request $request)
    {
        $tagihan = Tagihan::findOrFail($request->id);

        $params = [
            'payment_type' => 'bank_transfer',
            'transaction_details' => [
                'order_id' => 'TAGIHAN-' . $tagihan->id . '-' . time(),
                'gross_amount' => (int) $tagihan->nominal,
            ],
            'bank_transfer' => [
                'bank' => 'bca',
            ],
            'item_details' => [
                [
                    'id' => 'item-' . $tagihan->id,
                    'price' => (int) $tagihan->nominal,
                    'quantity' => 1,
                    'name' => $tagihan->nama_tagihan,
                ]
            ],
            'customer_details' => [
                'first_name' => 'Siswa',
                'email' => 'dummy@example.com',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'snapToken' => $snapToken,
        ]);
    }
}
