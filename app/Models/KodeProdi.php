<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeProdi extends Model
{
    use HasFactory;

    protected $table = 'kode_prodi'; // Nama tabel pada database

    protected $primaryKey = 'prodi_ID'; // Nama primary key

    protected $fillable = ['prodi_ID', 'program_studi']; // Kolom yang dapat diisi secara massal

    public $timestamps = false; // Tidak menggunakan kolom created_at dan updated_at
}

