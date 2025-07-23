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
        $data = DB::table('tagihan as tg')
            ->leftJoin('siswa as s', 'tg.siswa_id', '=', 's.id')
            ->where('tg.status_pembayaran', 'lunas')
            ->whereMonth('tg.updated_at', $this->bulan)
            ->whereYear('tg.updated_at', $this->tahun)
            ->orderBy('tg.updated_at', 'desc')
            ->select(
                's.nama as nama_siswa',
                'tg.nama_tagihan',
                'tg.periode',
                'tg.nominal as jumlah_bayar',
                DB::raw("CASE
                WHEN tg.deskripsi LIKE '%Tagihan otomatis setelah pendaftaran%' THEN 'Virtual Account'
                ELSE 'Manual/Upload'
            END as metode"),
                DB::raw("'Lunas' as status"),
                'tg.updated_at as tanggal_bayar'
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
