<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class Ta extends Model
{
    protected $table = 'tas';


    public static function ta_mahasiswa()
    {
        $ta_mahasiswa = DB::table('tas')
            ->join('mahasiswa', 'mahasiswa.mhs_nim', '=', 'tas.mhs_nim')
            ->select('tas.*', 'mahasiswa.mhs_nim', 'mahasiswa.mhs_nama')
            ->orderBy('mahasiswa.mhs_nim', 'asc')
            ->get();
        return $ta_mahasiswa;
    }
}
