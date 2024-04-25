<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalSidang extends Model
{
    protected $table = 'jadwal_sidang';

    public function sesiTa()
    {
        return $this->belongsTo(SesiTa::class, 'sesi_id', 'sesi_id');
    }
}
