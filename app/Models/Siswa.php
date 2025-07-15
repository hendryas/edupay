<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'siswa';

    protected $fillable = [
        'orang_tua_id',
        'nis',
        'nama',
        'kelas',
        'nomor_wa',
    ];

    protected $dates = ['deleted_at'];
}
