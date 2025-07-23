<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\TransaksiPembayaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

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
            $tagihanId = $orderId; // Atau pecah jika perlu: explode('-', $orderId)[1]
            $paymentMethod = $payload['payment_type'] ?? 'midtrans';
            $paidAt = $payload['settlement_time'] ?? now();
            $vaNumber = $payload['va_numbers'][0]['va_number'] ?? null;

            $tagihan = Tagihan::find($tagihanId);
            if ($tagihan && $tagihan->status_pembayaran !== 'lunas') {
                // Update tagihan
                $tagihan->status_pembayaran = 'lunas';
                $tagihan->save();
                Log::info("Tagihan ID $tagihanId diupdate ke lunas");

                // Buat transaksi pembayaran
                TransaksiPembayaran::create([
                    'siswa_id'        => $tagihan->siswa_id,
                    'tagihan_id'      => $tagihan->id,
                    'tanggal_bayar'   => \Carbon\Carbon::parse($paidAt)->format('Y-m-d'),
                    'jumlah_bayar'    => $tagihan->nominal,
                    'metode'          => 'virtual account',
                    'status'          => 'lunas',
                    'dibuat_oleh'     => 'system', // Karena via webhook, bukan user langsung
                    'bukti_transfer'  => $vaNumber,
                ]);

                Log::info("Transaksi pembayaran berhasil dicatat untuk Tagihan ID $tagihanId");
            }
        }

        return response()->json(['message' => 'OK'], 200);
    }
}
