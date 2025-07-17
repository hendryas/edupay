<?php

namespace App\Http\Controllers;

use App\Models\BillingType;
use App\Models\OrangTua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingParentController extends Controller
{
    public function index()
    {
        $orangTua = OrangTua::all();
        $billingTypes = BillingType::all();

        $dataTagihan = DB::table('tagihan as a')
            ->leftJoin('siswa as b', 'b.id', '=', 'a.siswa_id')
            ->leftJoin('orang_tua as c', 'c.siswa_id', '=', 'a.siswa_id')
            ->select(
                'a.id',
                'a.nama_tagihan as tagihan_siswa',
                'a.nominal as nominal_tagihan',
                'b.nama as nama_siswa',
                'c.nama_lengkap as nama_orang_tua',
            )
            ->get();
        return view('management.billingparent.index', compact('orangTua', 'billingTypes', 'dataTagihan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'orang_tua_id' => 'required|exists:orang_tua,id',
            'billing_type_id' => 'required|exists:billing_types,id',
        ]);

        $orangTua = OrangTua::findOrFail($request->orang_tua_id);

        $billingTypes = BillingType::findOrFail($request->billing_type_id);
        $nama_tagihan = $billingTypes->name;
        $amount = $billingTypes->amount;
        $periode = now()->format('Y-m');

        $tagihanId = DB::table('tagihan')->insert([
            'siswa_id' => $orangTua->siswa_id,
            'biling_type_id' => $request->billing_type_id,
            'nama_tagihan' => $nama_tagihan,
            'nominal' => $amount,
            'status_pembayaran' => 'pending',
            'periode' => $periode,
            'created_at' => now(),
        ]);

        $siswa = DB::table('siswa')->where('id', $orangTua->siswa_id)->first();

        $pesan = "📢 *Informasi Tagihan*\n\n" .
         "Yth. Bapak/Ibu *{$orangTua->nama_lengkap}*,\n" .
         "Kami informasikan bahwa *tagihan* untuk ananda *{$siswa->nama}* telah diterbitkan dengan rincian berikut:\n\n" .
         "🔹 *Jenis Tagihan:* {$nama_tagihan}\n" .
         "🔹 *Jumlah:* Rp" . number_format($amount, 0, ',', '.') . "\n" .
         "🔹 *Status:* Belum dibayar (pending)\n\n" .
         "Mohon untuk segera melakukan pembayaran agar proses administrasi berjalan lancar.\n\n" .
         "Jika Bapak/Ibu memiliki pertanyaan lebih lanjut, silakan hubungi pihak administrasi sekolah.\n\n" .
         "Terima kasih atas perhatian dan kerja samanya 🙏\n\n" .
         "*SMK Tunas Harapan*";
         \App\Services\FonnteService::send($orangTua->no_hp, $pesan);

        return response()->json(['success' => true]);
    }

    public function update() {}
    public function destroy() {}
}
