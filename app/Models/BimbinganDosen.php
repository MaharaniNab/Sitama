<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BimbinganDosen extends Model
{
    use HasFactory;

    protected $table = 'bimbingans'; // Nama tabel jika tidak mengikuti konvensi Laravel
    protected $primaryKey = 'bimbingan_id';

    protected $fillable = ['dosen_nip', 'ta_id', 'urutan', 'verified'];
    public $timestamps = false;

    // Metode untuk menghapus bimbingan berdasarkan ID
    public static function destroyBimbingan($id)
    {
        try {
            $bimbingan = self::findOrFail($id);
            $bimbingan->delete();
            return true; // Return true jika berhasil dihapus
        } catch (\Throwable $th) {
            return false; // Return false jika terjadi kesalahan
        }
    }

    // Definisi relasi dengan model Mahasiswa
    public static function ta_mahasiswa()
    {
        $ta_mahasiswa = DB::table('tas')
            ->join('mahasiswa', 'mahasiswa.mhs_nim', '=', 'tas.mhs_nim')
            ->leftJoin('bimbingans', 'bimbingans.ta_id', '=', 'tas.ta_id')
            ->leftJoin('dosen', 'dosen.dosen_nip', '=', 'bimbingans.dosen_nip')
            ->select(
                'tas.ta_id',
                'tas.ta_judul',
                'tas.verified',
                'tas.tahun_akademik',
                'mahasiswa.mhs_nim',
                'mahasiswa.mhs_nama',
                DB::raw('GROUP_CONCAT(bimbingans.dosen_nip ORDER BY bimbingan_id) as dosen_nip'),
                DB::raw('GROUP_CONCAT(dosen.dosen_nama ORDER BY bimbingan_id SEPARATOR "|") as dosen_nama'),
                DB::raw('GROUP_CONCAT(bimbingans.bimbingan_id ORDER BY bimbingan_id) as bimbingan_id')
            )
            ->orderBy('mahasiswa.mhs_nim', 'asc')
            ->groupBy(
                'tas.ta_id',
                'tas.ta_judul',
                'tas.verified',
                'tas.tahun_akademik',
                'mahasiswa.mhs_nim',
                'mahasiswa.mhs_nama'
            )
            ->get();

        $mahasiswa_comp = [];

        foreach ($ta_mahasiswa as $tm) {
            $dosen_nip_array = explode(',', $tm->dosen_nip);
            $dosen_nama_array = explode('|', $tm->dosen_nama);
            $bimbingan_id_array = explode(',', $tm->bimbingan_id);

            $dosen = [];
            foreach ($dosen_nama_array as $key => $dosen_nama) {
                $dosen[] = [
                    'dosen_nama' => $dosen_nama,
                    'dosen_nip' => $dosen_nip_array[$key],
                    'bimbingan_id' => $bimbingan_id_array[$key]
                ];
            }
            $tm->dosen = $dosen;
            // unset($tm->dosen_nip);
            // unset($tm->dosen_nama);
            // unset($tm->bimbingan_id);

            $mahasiswa_comp[] = $tm;
        }

        // dd($mahasiswa_comp);
        // return $ta_mahasiswa;
        return $mahasiswa_comp;
    }

    public static function ta_mahasiswa2()
    {
        $email = Auth::user()->email;

        $ta_mahasiswa = DB::table('tas')
            ->join('mahasiswa', 'mahasiswa.mhs_nim', '=', 'tas.mhs_nim')
            ->leftJoin('bimbingans', 'bimbingans.ta_id', '=', 'tas.ta_id')
            ->leftJoin('dosen', 'dosen.dosen_nip', '=', 'bimbingans.dosen_nip')
            ->select(
                'tas.ta_id',
                'tas.ta_judul',
                'tas.verified',
                'tas.tahun_akademik',
                'mahasiswa.mhs_nim',
                'mahasiswa.mhs_nama',
                DB::raw('GROUP_CONCAT(bimbingans.dosen_nip ORDER BY bimbingan_id) as dosen_nip'),
                DB::raw('GROUP_CONCAT(dosen.dosen_nama ORDER BY bimbingan_id SEPARATOR "|") as dosen_nama'),
                DB::raw('GROUP_CONCAT(bimbingans.bimbingan_id ORDER BY bimbingan_id) as bimbingan_id')
            )
            ->orderBy('mahasiswa.mhs_nim', 'asc')
            ->groupBy(
                'tas.ta_id',
                'tas.ta_judul',
                'tas.verified',
                'tas.tahun_akademik',
                'mahasiswa.mhs_nim',
                'mahasiswa.mhs_nama'
            )
            ->where('dosen.email',$email );
        return $ta_mahasiswa;
    }

    public static function mahasiswa()
    {
        $mhs = DB::table('mahasiswa')
            ->get();
        // dd($mhs);
        return $mhs;
    }
}
