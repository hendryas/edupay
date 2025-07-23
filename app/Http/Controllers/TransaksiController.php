<?php

namespace App\Http\Controllers;

use App\Models\OrangTua;
use App\Models\Tagihan;
use App\Models\TransaksiPembayaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    public function index()
    {
        $user_id = session('user_id');

        $orangTua = OrangTua::where('user_id', $user_id)->first();

        if (!$orangTua) {
            return abort(404, 'Data orang tua tidak ditemukan');
        }

        $siswa_id = $orangTua->siswa_id;

        $tagihanList = DB::table('tagihan as a')
            ->leftJoin('siswa as b', 'a.siswa_id', '=', 'b.id')
            ->where('a.siswa_id', $siswa_id)
            ->orderBy('a.created_at', 'desc')
            ->select('a.*', 'b.nama as nama_siswa')
            ->get();

        foreach ($tagihanList as $tagihan) {
            $hasTransaksi = DB::table('transaksi_pembayaran')
                ->where('tagihan_id', $tagihan->id)
                ->exists();

            $tagihan->has_transaksi = $hasTransaksi;
        }

        return view('admin.transaksi.index', compact('tagihanList'));
    }

    public function history($tagihan_id)
    {
        $historyList = DB::table('transaksi_pembayaran as a')
            ->leftJoin('siswa as b', 'a.siswa_id', '=', 'b.id')
            ->leftJoin('tagihan as c', 'a.tagihan_id', '=', 'c.id')
            ->where('a.tagihan_id', $tagihan_id)
            ->orderBy('a.tanggal_bayar', 'desc')
            ->select(
                'a.*',
                'b.nama as nama_siswa',
                'c.nama_tagihan',
                'c.nominal as nominal_tagihan',
                'c.periode',
                'c.status_pembayaran as status_tagihan'
            )
            ->get();


        return view('admin.transaksi.history', compact('historyList'));
    }


    public function formVA($id)
    {
        $tagihan = DB::table('tagihan as a')
            ->leftJoin('siswa as b', 'a.siswa_id', '=', 'b.id')
            ->where('a.id', $id)
            ->orderBy('a.created_at', 'desc')
            ->select('a.*', 'b.nama as nama_siswa')
            ->get();

        return view('pembayaran.form_va', compact('tagihan'));
    }

    public function formUpload($id)
    {
        $tagihan = DB::table('tagihan as a')
            ->leftJoin('siswa as b', 'a.siswa_id', '=', 'b.id')
            ->where('a.id', $id)
            ->orderBy('a.created_at', 'desc')
            ->select('a.*', 'b.nama as nama_siswa')
            ->first();

        return view('pembayaran.form_upload', compact('tagihan'));
    }

    public function uploadBukti(Request $request)
    {
        $request->validate([
            'tagihan_id' => 'required|exists:tagihan,id',
            'bukti_transfer' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $tagihan = DB::table('tagihan')->where('id', $request->tagihan_id)->first();

        if (!$tagihan) {
            return response()->json(['message' => 'Tagihan tidak ditemukan.'], 404);
        }

        $fotoPath = 'pendaftaran/uploadbukti';
        Storage::disk('public')->makeDirectory($fotoPath);

        $filePath = $request->file('bukti_transfer')->store($fotoPath, 'public');

        $user_id = session('user_id');
        $getUser = OrangTua::where('user_id', $user_id)->first();
        $nama = $getUser ? $getUser->nama_lengkap : 'unknown';

        TransaksiPembayaran::create([
            'siswa_id' => $tagihan->siswa_id,
            'tagihan_id' => $tagihan->id,
            'tanggal_bayar' => Carbon::now(),
            'jumlah_bayar' => $tagihan->nominal, // atau bisa disesuaikan input user
            'metode' => 'transfer',
            'status' => 'pending',
            'dibuat_oleh' => $nama, // atau custom sesuai sistem
            'bukti_transfer' => $filePath,
        ]);

        if ($getUser->no_hp) {
            $pesan = "Halo {$getUser->nama_lengkap},\n\n" .
                    "Bukti pembayaran untuk tagihan *{$tagihan->nama_tagihan}* telah berhasil diunggah.\n\n" .
                    "Silakan menunggu proses *verifikasi oleh admin*. Proses validasi akan dilakukan dalam waktu maksimal *3x24 jam*.\n\n" .
                    "Kami akan segera menginformasikan statusnya setelah proses verifikasi selesai.\n\n" .
                    "Terima kasih atas kerja samanya 🙏\n\n" .
                    "*SMK Tunas Harapan*";
            \App\Services\FonnteService::send($getUser->no_hp, $pesan);
        }

        return response()->json(['message' => 'Bukti transfer berhasil diunggah.']);
    }

    public function verifikasi() {}
}
