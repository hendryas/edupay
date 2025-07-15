<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tagihan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tagihan';

    protected $fillable = [
        'siswa_id',
        'biling_type_id',
        'nama_tagihan',
        'nominal',
        'periode',
        'deskripsi',
        'status_pembayaran',
    ];

    protected $dates = ['deleted_at'];
}
