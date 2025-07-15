<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;

class KwitansiController extends Controller
{
    public function index()
    {
        $riwayatList = DB::table('transaksi_pembayaran as t')
            ->leftJoin('tagihan as tg', 't.tagihan_id', '=', 'tg.id')
            ->leftJoin('siswa as s', 't.siswa_id', '=', 's.id')
            ->where('tg.biling_type_id', 1) // hanya tagihan pendaftaran
            ->orderBy('t.tanggal_bayar', 'desc')
            ->select(
                't.id',
                's.nama as nama_siswa',
                'tg.nama_tagihan',
                'tg.periode',
                't.jumlah_bayar',
                't.metode',
                't.status',
                't.tanggal_bayar'
            )
            ->get();

        return view('kwitansi.index', compact('riwayatList'));
    }


    public function show($id)
    {
        $data = DB::table('transaksi_pembayaran as t')
            ->leftJoin('tagihan as tg', 't.tagihan_id', '=', 'tg.id')
            ->leftJoin('siswa as s', 't.siswa_id', '=', 's.id')
            ->where('t.id', $id)
            ->select(
                't.*',
                'tg.nama_tagihan',
                'tg.periode',
                's.nama as nama_siswa'
            )
            ->first();

        if (!$data) {
            abort(404, 'Data tidak ditemukan');
        }

        return view('kwitansi.show', compact('data'));
    }

    public function cetak($id)
    {
        $data = DB::table('transaksi_pembayaran as t')
            ->leftJoin('tagihan as tg', 't.tagihan_id', '=', 'tg.id')
            ->leftJoin('siswa as s', 't.siswa_id', '=', 's.id')
            ->where('t.id', $id)
            ->select(
                't.*',
                'tg.nama_tagihan',
                'tg.periode',
                's.nama as nama_siswa'
            )
            ->first();

        if (!$data) {
            abort(404, 'Data tidak ditemukan');
        }

        $pdf = FacadePdf::loadView('kwitansi.pdf', compact('data'));
        return $pdf->stream('kwitansi_' . $data->nama_siswa . '.pdf');
    }
}
