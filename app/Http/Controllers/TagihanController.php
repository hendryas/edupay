<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Siswa;
use App\Models\OrangTua;
use App\Models\TransaksiPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TagihanController extends Controller
{
    public function index()
    {
        $user_id = session('user_id'); // atau bisa pakai Auth::id()
        $orangTua = OrangTua::where('user_id', $user_id)->first();

        if (!$orangTua) {
            return view('tagihan.index', [
                'siswa' => null,
                'tagihanList' => [],
                'message' => 'Data orang tua tidak ditemukan.'
            ]);
        }

        $siswa = Siswa::where('orang_tua_id', $orangTua->id)->first();

        if (!$siswa) {
            return view('tagihan.index', [
                'siswa' => null,
                'tagihanList' => [],
                'message' => 'Data siswa belum tersedia.'
            ]);
        }

        // JOIN tagihan dengan transaksi_pembayaran
        $tagihanList = Tagihan::leftJoin('transaksi_pembayaran', 'tagihan.id', '=', 'transaksi_pembayaran.tagihan_id')
            ->where('tagihan.siswa_id', $siswa->id)
            ->select(
                'tagihan.*',
                'transaksi_pembayaran.metode as metode_pembayaran',
                'transaksi_pembayaran.status as status_pembayaran',
                'transaksi_pembayaran.bukti_transfer'
            )
            ->get();

        return view('tagihan.index', compact('siswa', 'tagihanList'));
    }


    public function bayar(Request $request)
    {
        $request->validate([
            'tagihan_id' => 'required|exists:tagihan,id',
            'metode' => 'required|in:va,transfer',
            'bukti_transfer' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $tagihan = Tagihan::findOrFail($request->tagihan_id);

        if ($tagihan->status_pembayaran === 'lunas') {
            return response()->json(['status' => 'warning', 'message' => 'Tagihan sudah dibayar.']);
        }

        $metode = $request->metode;
        $statusPembayaran = 'pending';
        $buktiPath = null;

        if ($metode === 'va') {
            $statusPembayaran = 'lunas';

            // Update tagihan
            $tagihan->update([
                'status_pembayaran' => $statusPembayaran,
                'metode_pembayaran' => $metode,
            ]);
        } elseif ($metode === 'transfer') {
            if (!$request->hasFile('bukti_transfer')) {
                return response()->json(['status' => 'error', 'message' => 'Bukti transfer wajib diunggah.']);
            }

            $folder = 'bukti_transfer';
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }

            $buktiPath = $request->file('bukti_transfer')->store($folder, 'public');

            // Update tagihan status menunggu verifikasi
            $tagihan->update([
                'status_pembayaran' => 'menunggu_verifikasi',
                'metode_pembayaran' => $metode,
                'bukti_transfer' => $buktiPath,
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Metode pembayaran tidak valid.']);
        }

        // Simpan ke transaksi_pembayaran
        TransaksiPembayaran::create([
            'siswa_id'       => $tagihan->siswa_id,
            'tagihan_id'     => $tagihan->id,
            'tanggal_bayar'  => now()->toDateString(),
            'jumlah_bayar'   => $tagihan->nominal,
            'metode'         => $metode,
            'status'         => $statusPembayaran,
            'dibuat_oleh'    => Auth::user()->name ?? 'Sistem',
            'bukti_transfer' => $buktiPath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $metode === 'va'
                ? 'Pembayaran VA berhasil.'
                : 'Bukti transfer berhasil diunggah. Menunggu verifikasi.',
        ]);
    }

    // Admin
    public function dataPendaftaran()
    {
        $tagihanList = DB::table('tagihan as a')
            ->leftJoin('siswa as b', 'a.siswa_id', '=', 'b.id')
            ->leftJoin('orang_tua as o', 'b.orang_tua_id', '=', 'o.id') // pastikan relasi ini ada
            ->leftJoin('transaksi_pembayaran as c', 'a.id', '=', 'c.tagihan_id')
            ->select(
                'a.*',
                'b.nama as nama_siswa',
                'b.orang_tua_id',
                'c.metode',
                'c.bukti_transfer',
                'c.status as status_transaksi'
            )
            ->get();

        return view('admin.tagihan.pendaftaran', compact('tagihanList'));
    }

    public function veriftagihan(Request $request)
    {
        $request->validate([
            'tagihan_id' => 'required|exists:tagihan,id',
            'orang_tua_id' => 'required|exists:orang_tua,id',
            'status' => 'required|in:lunas,gagal',
        ]);

        $orangTua = OrangTua::find($request->orang_tua_id);

        if (!$orangTua) {
            return view('tagihan.index', [
                'siswa' => null,
                'tagihanList' => [],
                'message' => 'Data orang tua tidak ditemukan.'
            ]);
        }

        $siswa = Siswa::where('orang_tua_id', $orangTua->id)->first();

        if (!$siswa) {
            return view('tagihan.index', [
                'siswa' => null,
                'tagihanList' => [],
                'message' => 'Data siswa belum tersedia.'
            ]);
        }

        // Update status pembayaran pada transaksi
        DB::table('transaksi_pembayaran')
            ->where('tagihan_id', $request->tagihan_id)
            ->update([
                'status' => $request->status,
                'updated_at' => now(),
            ]);

        // Update status pada tagihan
        DB::table('tagihan')
            ->where('id', $request->tagihan_id)
            ->update([
                'status_pembayaran' => $request->status,
                'updated_at' => now(),
            ]);

        // Update status pendaftaran jika ada data di registration_schools
        DB::table('registration_schools')
            ->where('orang_tua_id', $orangTua->id)
            ->where('siswa_nama', $siswa->nama)
            ->update([
                'status_pendaftaran' => $request->status,
                'updated_at' => now(),
            ]);

        if ($request->status === 'lunas') {
            $tagihan = Tagihan::find($request->tagihan_id);
            $siswa = Siswa::find($tagihan->siswa_id);

            if ($siswa && $orangTua->no_hp) {
                $pesan = "Halo {$siswa->nama}, pembayaran untuk tagihan *{$tagihan->nama_tagihan}* telah berhasil diverifikasi sebagai *LUNAS*. Terima kasih 🙏";
                \App\Services\FonnteService::send($orangTua->no_hp, $pesan);
            }
        }

        return redirect()->back()->with('success', 'Status pembayaran berhasil diverifikasi.');
    }
}
