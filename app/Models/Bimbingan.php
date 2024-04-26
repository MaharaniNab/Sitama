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
        $ta_mahasiswa = DB::table('bimbingans as b1')
            ->join('bimbingans as b2', function ($join) {
                $join->on('b1.ta_id', '=', 'b2.ta_id')
                    ->where('b1.urutan', '=', 1)
                    ->where('b2.urutan', '=', 2);
            })
            ->join('tas', 'tas.ta_id', '=', 'b1.ta_id')
            ->join('mahasiswa', 'mahasiswa.mhs_nim', '=', 'tas.mhs_nim')
            ->join('dosen as dosen_1', 'dosen_1.dosen_nip', '=', 'b1.dosen_nip')
            ->join('dosen as dosen_2', 'dosen_2.dosen_nip', '=', 'b2.dosen_nip')
            ->select(
                'tas.ta_id',
                'mahasiswa.mhs_nim',
                'mahasiswa.mhs_nama',
                DB::raw('CONCAT(dosen_1.dosen_nama, " - ", dosen_2.dosen_nama) as nama_pembimbing'),
                'tas.ta_judul',
                'tas.tahun_akademik',
                'b1.verified'
            )
            ->get();

        return $ta_mahasiswa;
    }
}
