<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TagihanPendaftaranExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        $data = DB::table('tagihan as t')
            ->leftJoin('siswa as s', 't.siswa_id', '=', 's.id')
            ->leftJoin('transaksi_pembayaran as tp', 't.id', '=', 'tp.tagihan_id')
            ->where('t.biling_type_id', 1)
            ->select(
                's.nama as nama_siswa',
                't.nama_tagihan',
                't.periode',
                't.nominal',
                'tp.metode',
                't.status_pembayaran',
                'tp.tanggal_bayar'
            )
            ->orderBy('t.created_at', 'desc')
            ->get();

        $totalNominal = $data->sum('nominal');

        // Tambahkan baris total
        $data->push((object)[
            'nama_siswa' => '',
            'nama_tagihan' => '',
            'periode' => '',
            'nominal' => $totalNominal,
            'metode' => '',
            'status_pembayaran' => '',
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
            'Nominal',
            'Metode Pembayaran',
            'Status Pembayaran',
            'Tanggal Bayar'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling header (row 1)
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '4CAF50'], // Hijau tua
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
            'alignment' => [
                'horizontal' => 'center',
            ],
        ]);

        // Styling semua sel dengan border
        $lastRow = 1 + $this->collection()->count(); // jumlah data + header
        $sheet->getStyle("A{$lastRow}:G{$lastRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => 'FFF9C4'], // Kuning muda
            ],
        ]);
        $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ]);
    }
}
