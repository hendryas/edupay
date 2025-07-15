<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationSchool extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'registration_schools';

    protected $fillable = [
        'user_id',
        'orang_tua_id',
        'wali_nama',
        'wali_hp',
        'wali_alamat',
        'siswa_nama',
        'siswa_nisn',
        'siswa_tempat_lahir',
        'siswa_tanggal_lahir',
        'siswa_jenis_kelamin',
        'siswa_jurusan',
        'foto_siswa',
        'akta_kelahiran',
        'kartu_keluarga',
        'ijazah_terakhir',
        'tanggal_pendaftaran',
        'status_pendaftaran',
    ];

    protected $dates = ['deleted_at'];
}
