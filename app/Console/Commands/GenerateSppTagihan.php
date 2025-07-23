<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\OrangTua;
use App\Services\FonnteService;

class GenerateSppTagihan extends Command
{
    protected $signature = 'tagihan:generate-spp';
    protected $description = 'Generate tagihan SPP bulanan secara otomatis';

    public function handle()
    {
        $periode = now()->format('F Y'); // contoh: Juli 2025
        $billing = DB::table('billing_types')->where('code', 'SPP')->first();

        if (!$billing) {
            $this->error("Jenis tagihan dengan kode 'SPP' tidak ditemukan di tabel billing_types.");
            return;
        }

        $orangTuas = OrangTua::with('siswa')->get();
        $jumlahDibuat = 0;

        foreach ($orangTuas as $ortu) {
            $siswa = $ortu->siswa;

            if (!$siswa) continue;

            $sudahAda = DB::table('tagihan')
                ->where('siswa_id', $siswa->id)
                ->where('biling_type_id', $billing->id)
                ->where('periode', $periode)
                ->exists();

            if ($sudahAda) continue;

            // Insert tagihan baru
            DB::table('tagihan')->insert([
                'siswa_id' => $siswa->id,
                'biling_type_id' => $billing->id,
                'nama_tagihan' => $billing->name,
                'nominal' => $billing->amount,
                'status_pembayaran' => 'pending',
                'periode' => $periode,
                'deskripsi' => 'Tagihan otomatis SPP bulan ' . $periode,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Kirim notifikasi WhatsApp
            $pesan = "📢 *Tagihan SPP Bulan {$periode}*\n\n" .
                "Yth. Bapak/Ibu *{$ortu->nama_lengkap}*,\n" .
                "Tagihan SPP untuk ananda *{$siswa->nama}* telah diterbitkan.\n\n" .
                "🔹 *Jumlah:* Rp" . number_format($billing->amount, 0, ',', '.') . "\n" .
                "🔹 *Status:* Belum dibayar\n\n" .
                "Mohon segera melakukan pembayaran sebelum tanggal 10 bulan ini.\n\n" .
                "*Terima kasih.*\n*SMK Tunas Harapan*";

            FonnteService::send($ortu->no_hp, $pesan);

            $jumlahDibuat++;
        }

        $this->info("✅ {$jumlahDibuat} tagihan SPP berhasil dibuat untuk periode {$periode}.");
    }
}
