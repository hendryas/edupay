<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    public function index()
    {
        $riwayatList = DB::table('transaksi_pembayaran as t')
            ->leftJoin('tagihan as tg', 't.tagihan_id', '=', 'tg.id')
            ->leftJoin('siswa as s', 't.siswa_id', '=', 's.id')
            ->orderBy('t.tanggal_bayar', 'desc')
            ->select(
                's.nama as nama_siswa',
                'tg.nama_tagihan',
                'tg.periode',
                't.jumlah_bayar',
                't.metode',
                't.status',
                't.tanggal_bayar',
                't.bukti_transfer'
            )
            ->get();

        return view('riwayat.pembayaran', compact('riwayatList'));
    }
}
