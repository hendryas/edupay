<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrangTua extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orang_tua';

    protected $fillable = [
        'user_id',
        'siswa_id',
        'nama_lengkap',
        'jenis_kelamin',
        'no_hp',
        'email',
        'alamat',
        'pekerjaan',
        'hubungan_dengan_siswa',
    ];

    protected $dates = ['deleted_at'];
}
