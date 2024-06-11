<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'mhs_nim';
    protected $fillable = ['mhs_nim', 'mhs_nama', 'prodi_ID'];

    public function kode_prodi()
    {
        return $this->belongsTo(KodeProdi::class, 'prodi_ID', 'prodi_ID');
    }
}
