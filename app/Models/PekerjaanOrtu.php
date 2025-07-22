<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PekerjaanOrtu extends Model
{
    use SoftDeletes;

    // Secara eksplisit arahkan ke tabel 'pekerjaan_ortu'
    protected $table = 'pekerjaan_ortu';

    // Kolom-kolom yang dapat diisi massal
    protected $fillable = [
        'kode_pekerjaan',
        'nama_pekerjaan',
    ];

    // Jika ingin memastikan deleted_at dikenali sebagai Carbon instance
    protected $dates = ['deleted_at'];
}
