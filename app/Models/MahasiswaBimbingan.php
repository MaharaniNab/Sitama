<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MahasiswaBimbingan extends Model
{
    use HasFactory;

    protected $table = 'bimbingans';
    protected $primaryKey = 'bimbingan_id';

    public static function ta_mahasiswa()
    {
        $email = Auth::user()->email;

        $ta_mahasiswa = DB::table('tas')
            ->join('mahasiswa', 'mahasiswa.mhs_nim', '=', 'tas.mhs_nim')
            ->join('bimbingans', 'bimbingans.ta_id', '=', 'tas.ta_id')
            ->join('dosen', 'dosen.dosen_nip', '=', 'bimbingans.dosen_nip')
            ->where('dosen.email', $email)
            ->select(
                'tas.ta_id',
                'tas.ta_judul',
                'tas.verified',
                'tas.tahun_akademik',
                'mahasiswa.mhs_nim',
                'mahasiswa.mhs_nama',
                'bimbingans.dosen_nip',
                'dosen.dosen_nama',
                'bimbingans.urutan'
            )
            ->orderBy('mahasiswa.mhs_nim', 'asc')
            ->get();

        $organizedData = [];

        foreach ($ta_mahasiswa as $data) {
            if (!isset($organizedData[$data->ta_id])) {
                $organizedData[$data->ta_id] = [
                    'ta_id' => $data->ta_id,
                    'mhs_nim' => $data->mhs_nim,
                    'mhs_nama' => $data->mhs_nama,
                    'ta_judul' => $data->ta_judul,
                    'verified' => $data->verified,
                    'tahun_akademik' => $data->tahun_akademik,
                    'dosen' => []
                ];
            }
            $organizedData[$data->ta_id]['dosen'][] = [
                'dosen_nama' => $data->dosen_nama,
                'urutan' => $data->urutan

            ];
        }

        return array_values($organizedData);
    }
}