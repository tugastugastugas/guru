<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guru extends Authenticatable
{
    use HasFactory;

    protected $table = 'guru'; // Menetapkan nama tabel jika tidak sesuai dengan konvensi
    protected $primaryKey = 'id_guru'; // Menetapkan primary key yang benar

    // Daftar kolom yang dapat diisi massal
    protected $fillable = [
        'nama_guru',
        'mapel_guru',
        'created_at',
        'updated_at',
    ];
}
