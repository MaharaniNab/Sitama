<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TaRoleDosen extends Model
{
    protected $table = 'tas';
    protected $primaryKey = "ta_id";
    protected $fillable = [
        'mhs_nim',
        'ta_judul',
        'thn_akademik',
        'created_by',
        'created_at',
        'verfied_by'
    ];
    public $timestamps = false;

    // Relationship with Mahasiswa model


    // Method to fetch TaSidang data with necessary relations
    public static function TaSidang()
    {
        $taSidang = DB::table('ta_sidang')
            ->join('tas', 'ta_sidang.ta_id', '=', 'tas.ta_id')
            ->join('jadwal_sidang', 'ta_sidang.jadwal_id', '=', 'jadwal_sidang.jadwal_id')
            ->join('sesi_ta', 'jadwal_sidang.sesi_id', '=', 'sesi_ta.sesi_id')
            ->join('ruangan_ta', 'jadwal_sidang.ruangan_id', '=', 'ruangan_ta.ruangan_id')
            ->join('bimbingans', 'tas.ta_id', '=', 'bimbingans.ta_id')
            ->join('dosen', 'bimbingans.dosen_nip', '=', 'dosen.dosen_nip')
            ->join('mahasiswa', 'tas.mhs_nim', '=', 'mahasiswa.mhs_nim')
            ->select('ta_sidang.*', 'tas.mhs_nim', 'mahasiswa.mhs_nama', 'sesi_ta.sesi_waktu_mulai', 'sesi_ta.sesi_waktu_selesai', 'ruangan_ta.ruangan_nama', 'dosen.dosen_nama')
            ->orderBy('mahasiswa.mhs_nim', 'asc')
            ->get();

        return $taSidang;
    }
    public static function dataTa($id)
    {
        $dataTa = DB::table('tas')
            ->join('mahasiswa', 'mahasiswa.mhs_nim', '=', 'tas.mhs_nim')
            ->join('users', 'users.email', '=', 'mahasiswa.email')
            ->where('users.id', '=', $id)
            ->select('tas.*')
            ->first();

        return $dataTa;
    }

    public static function detailKelayakan($ta_id)
    {
        $user = Auth::user();

        $ta = DB::table('tas')
            ->join('mahasiswa', 'mahasiswa.mhs_nim', '=', 'tas.mhs_nim')
            ->join('ta_sidang', 'ta_sidang.ta_id', '=', 'tas.ta_id')
            ->join('jadwal_sidang', 'jadwal_sidang.jadwal_id', '=', 'ta_sidang.jadwal_id')
            ->join('ruangan_ta', 'ruangan_ta.ruangan_id', '=', 'jadwal_sidang.ruangan_id')
            ->join('bimbingans', 'bimbingans.ta_id', '=', 'tas.ta_id')
            ->join('dosen', 'dosen.dosen_nip', '=', 'bimbingans.dosen_nip')
            ->join('users', 'users.email', '=', 'dosen.email')
            ->where('tas.ta_id', $ta_id)
            ->where('users.email', $user->email)
            ->select('*')
            ->first();

        return $ta;
    }
    public static function detailKelayakan2($ta_id)
    {
        $user = Auth::user();

        $ta = DB::table('tas')
            ->join('mahasiswa', 'mahasiswa.mhs_nim', '=', 'tas.mhs_nim')
            ->join('ta_sidang', 'ta_sidang.ta_id', '=', 'tas.ta_id')
            ->join('jadwal_sidang', 'jadwal_sidang.jadwal_id', '=', 'ta_sidang.jadwal_id')
            ->join('ruangan_ta', 'ruangan_ta.ruangan_id', '=', 'jadwal_sidang.ruangan_id')
            ->join('penilaian_penguji', 'penilaian_penguji.ta_sidang_id', '=', 'ta_sidang.ta_sidang_id')
            ->join('dosen', 'dosen.dosen_nip', '=', 'penilaian_penguji.dosen_nip')
            ->join('users', 'users.email', '=', 'dosen.email')
            ->where('tas.ta_id', $ta_id)
            ->where('users.email', $user->email)
            ->select('*')
            ->first();

        return $ta;
    }

    public static function detailpenguji($ta_id)
    {
        $user = Auth::user();

        $ta = DB::table('tas')
            ->join('mahasiswa', 'mahasiswa.mhs_nim', '=', 'tas.mhs_nim')
            ->join('ta_sidang', 'ta_sidang.ta_id', '=', 'tas.ta_id')
            ->select('*');

        return $ta;
    }

    public static function unsur_nilai_pembimbing()
    {

        $nilai_pembimbing = DB::table('unsur_nilai_pembimbing')
            ->select('*');

        return $nilai_pembimbing;
    }

    public static function unsur_nilai_penguji()
    {

        $nilai_penguji = DB::table('unsur_nilai_penguji')
            ->select('*');

        return $nilai_penguji;
    }

    public static function Revisi($ta_id)
    {
        $user = Auth::user();

        $revisi = DB::table('ta_sidang')
            ->join('tas', 'ta_sidang.ta_id', '=', 'tas.ta_id')
            ->join('jadwal_sidang', 'ta_sidang.jadwal_id', '=', 'jadwal_sidang.jadwal_id')
            ->join('sesi_ta', 'jadwal_sidang.sesi_id', '=', 'sesi_ta.sesi_id')
            ->join('ruangan_ta', 'jadwal_sidang.ruangan_id', '=', 'ruangan_ta.ruangan_id')
            ->join('bimbingans', 'tas.ta_id', '=', 'bimbingans.ta_id')
            ->join('dosen', 'bimbingans.dosen_nip', '=', 'dosen.dosen_nip')
            ->join('mahasiswa', 'tas.mhs_nim', '=', 'mahasiswa.mhs_nim')
            ->join('users', 'users.email', '=', 'dosen.email')
            ->select(
                'ta_sidang.*', 
                'tas.mhs_nim', 
                'mahasiswa.mhs_nama', 
                'sesi_ta.sesi_waktu_mulai', 
                'sesi_ta.sesi_waktu_selesai', 
                'ruangan_ta.ruangan_nama', 
                'dosen.dosen_nama', 
                'users.email',
                )
            ->where('ta_sidang.ta_id', $ta_id)
            ->where('users.email', $user->email)
            ->get();

        return $revisi;
    }

    public static function Revisi2($ta_id)
    {
        $user = Auth::user();

        $revisi = DB::table('ta_sidang')
            ->join('tas', 'ta_sidang.ta_id', '=', 'tas.ta_id')
            ->join('jadwal_sidang', 'ta_sidang.jadwal_id', '=', 'jadwal_sidang.jadwal_id')
            ->join('sesi_ta', 'jadwal_sidang.sesi_id', '=', 'sesi_ta.sesi_id')
            ->join('ruangan_ta', 'jadwal_sidang.ruangan_id', '=', 'ruangan_ta.ruangan_id')
            ->join('penilaian_penguji', 'penilaian_penguji.ta_sidang_id', '=', 'ta_sidang.ta_sidang_id')
            ->join('dosen', 'dosen.dosen_nip', '=', 'penilaian_penguji.dosen_nip')
            ->join('mahasiswa', 'tas.mhs_nim', '=', 'mahasiswa.mhs_nim')
            ->join('users', 'users.email', '=', 'dosen.email')
            ->select(
                'ta_sidang.*', 
                'tas.mhs_nim', 
                'mahasiswa.mhs_nama', 
                'sesi_ta.sesi_waktu_mulai', 
                'sesi_ta.sesi_waktu_selesai', 
                'ruangan_ta.ruangan_nama', 
                'dosen.dosen_nama', 
                'users.email',
                )
            ->where('ta_sidang.ta_id', $ta_id)
            ->where('users.email', $user->email)
            ->get();

        return $revisi;
    }

}
