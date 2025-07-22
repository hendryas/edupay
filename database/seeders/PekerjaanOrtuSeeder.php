<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PekerjaanOrtu;

class PekerjaanOrtuSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_pekerjaan' => '01', 'nama_pekerjaan' => 'Petani'],
            ['kode_pekerjaan' => '02', 'nama_pekerjaan' => 'Pedagang'],
            ['kode_pekerjaan' => '03', 'nama_pekerjaan' => 'PNS'],
            ['kode_pekerjaan' => '04', 'nama_pekerjaan' => 'Karyawan Swasta'],
            ['kode_pekerjaan' => '05', 'nama_pekerjaan' => 'TNI/Polri'],
            ['kode_pekerjaan' => '06', 'nama_pekerjaan' => 'Wiraswasta'],
            ['kode_pekerjaan' => '07', 'nama_pekerjaan' => 'Tidak Bekerja'],
        ];

        foreach ($data as $item) {
            PekerjaanOrtu::create($item);
        }
    }
}
