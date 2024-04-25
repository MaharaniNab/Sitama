<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class Ta extends Model
{
    protected $table = 'tas';

    // Di dalam model Ta
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mhs_nim');
    }

    public static function TaSidang()
    {
        $taSidang = DB::table('tas')
            ->join('mahasiswa', 'mahasiswa.mhs_nim', '=', 'tas.mhs_nim')
            ->select('tas.*', 'mahasiswa.mhs_nim', 'mahasiswa.nama_id')
            ->orderBy('mahasiswa.mhs_nim', 'asc')
            ->get();
        return $taSidang;
    }
}
