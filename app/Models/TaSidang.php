<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TaSidang extends Model
{
    protected $table = 'ta_sidang';
    protected $primaryKey = "ta_sidang_id";
    protected $fillable = [
        'ta_id', 
        'jadwal_id', 
        'dosen_nip',
        'judul_final', 
        'nilai_akhir', 
        'status_lulus',
        'revisi_file',
        'revisi_file_original',
        'verified'
    ];
    public $timestamps = false;

    public static function TaSidang($ta_sidang_id)
    {
        $user = Auth::user();

        $taSidang = DB::table('ta_sidang')
            ->join('tas', 'ta_sidang.ta_id', '=', 'tas.ta_id')
            ->join('jadwal_sidang', 'ta_sidang.jadwal_id', '=', 'jadwal_sidang.jadwal_id')
            ->join('sesi_ta', 'jadwal_sidang.sesi_id', '=', 'sesi_ta.sesi_id')
            ->join('ruangan_ta', 'jadwal_sidang.ruangan_id', '=', 'ruangan_ta.ruangan_id')
            ->join('bimbingans', 'tas.ta_id', '=', 'bimbingans.ta_id')
            ->join('dosen', 'bimbingans.dosen_nip', '=', 'dosen.dosen_nip')
            ->join('mahasiswa', 'tas.mhs_nim', '=', 'mahasiswa.mhs_nim')
            ->select('ta_sidang.*', 'tas.mhs_nim', 'mahasiswa.mhs_nama', 'sesi_ta.sesi_waktu_mulai', 'sesi_ta.sesi_waktu_selesai', 'ruangan_ta.ruangan_nama', 'dosen.dosen_nama')
            ->where('tas.ta_id', $ta_sidang_id)
            ->where('users.email', $user->email)
            ->get();

        return $taSidang;
    }

}
