<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, ShouldAutoSize, WithStyles};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapTransaksiBulananExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $data = DB::table('transaksi_pembayaran as t')
            ->leftJoin('tagihan as tg', 't.tagihan_id', '=', 'tg.id')
            ->leftJoin('siswa as s', 't.siswa_id', '=', 's.id')
            ->whereMonth('t.tanggal_bayar', $this->bulan)
            ->whereYear('t.tanggal_bayar', $this->tahun)
            ->where('tg.biling_type_id', 1)
            ->orderBy('t.tanggal_bayar', 'desc')
            ->select(
                's.nama as nama_siswa',
                'tg.nama_tagihan',
                'tg.periode',
                't.jumlah_bayar',
                't.metode',
                't.status',
                't.tanggal_bayar'
            )
            ->get();

        $total = $data->sum('jumlah_bayar');

        $data->push((object)[
            'nama_siswa' => '',
            'nama_tagihan' => '',
            'periode' => '',
            'jumlah_bayar' => $total,
            'metode' => '',
            'status' => '',
            'tanggal_bayar' => 'TOTAL'
        ]);

        return $data;
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Nama Tagihan',
            'Periode',
            'Jumlah Bayar',
            'Metode Pembayaran',
            'Status Pembayaran',
            'Tanggal Bayar'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = 1 + $this->collection()->count();

        // Header
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '4CAF50'],
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
            'alignment' => [
                'horizontal' => 'center',
            ],
        ]);

        // Baris total
        $sheet->getStyle("A{$lastRow}:G{$lastRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => 'FFF9C4'],
            ],
        ]);

        // Border semua sel
        $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ]);
    }
}
