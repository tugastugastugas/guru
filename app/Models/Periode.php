<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Periode extends Authenticatable
{
    use HasFactory;

    protected $table = 'periode'; // Menetapkan nama tabel jika tidak sesuai dengan konvensi
    protected $primaryKey = 'id_periode'; // Menetapkan primary key yang benar

    // Daftar kolom yang dapat diisi massal
    protected $fillable = [
        'nama_periode',
        'tgl_mulai',
        'tgl_akhir',
        'status',
        'created_at',
        'updated_at',
    ];
}
