<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ulasan extends Authenticatable
{
    use HasFactory;

    protected $table = 'ulasan'; // Menetapkan nama tabel jika tidak sesuai dengan konvensi
    protected $primaryKey = 'id_ulasan'; // Menetapkan primary key yang benar

    // Daftar kolom yang dapat diisi massal
    protected $fillable = [
        'id_user',
        'id_guru',
        'kritikan',
        'pujian',
        'created_at',
        'updated_at',
    ];
}
