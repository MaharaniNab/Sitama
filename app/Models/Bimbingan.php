<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Bimbingan extends Model
{
    use HasFactory;

    protected $table = 'bimbingans'; // Nama tabel jika tidak mengikuti konvensi Laravel
    protected $primaryKey = 'ta_id';

    // Definisi relasi dengan model Mahasiswa
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
