<?php

namespace App\Http\Controllers;

use App\Exports\RekapTransaksiBulananExport;
use App\Exports\TagihanPendaftaranExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
     public function index()
    {
        $tagihanList = DB::table('tagihan as t')
            ->leftJoin('siswa as s', 't.siswa_id', '=', 's.id')
            ->leftJoin('transaksi_pembayaran as tp', 't.id', '=', 'tp.tagihan_id')
            ->select(
                's.nama as nama_siswa',
                't.nama_tagihan',
                't.periode',
                't.nominal',
                'tp.metode',
                't.status_pembayaran',
                'tp.tanggal_bayar',
                'tp.bukti_transfer',
                't.created_at'
            )
            ->orderBy('t.created_at', 'desc')
            ->get();

        return view('admin.laporan.tagihan_pendaftaran', compact('tagihanList'));
    }

    public function export()
    {
        return Excel::download(new TagihanPendaftaranExport, 'laporan_tagihan_pendaftaran.xlsx');
    }

     public function rekapIndex()
    {
        return view('admin.laporan.rekap_bulanan_index');
    }

    public function rekapExport(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020'
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $namaFile = 'rekap_transaksi_bulanan_' . $bulan . '_' . $tahun . '.xlsx';

        return Excel::download(new RekapTransaksiBulananExport($bulan, $tahun), $namaFile);
    }
}
