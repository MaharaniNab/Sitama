<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BimbinganLogDosen extends Model
{
    use HasFactory;

    protected $table = "bimbingan_log";

    protected $primaryKey = "bimbingan_log_id";

    protected $fillable = [
        'bimbingan_id',
        'bimb_tgl',
        'bimb_judul',
        'bimb_desc',
        'bimb_file_original',
        'bimb_file',
        'bimb_status',
    ];

    public $timestamps = false;

    public static function BimbinganLog($ta_id)
    {
        $email = Auth::user()->email;
        
        $log = DB::table('bimbingan_log')
            ->join('bimbingans', 'bimbingan_log.bimbingan_id', '=', 'bimbingans.bimbingan_id')
            ->join('tas', 'tas.ta_id', '=', 'bimbingans.ta_id')
            ->join('mahasiswa', 'mahasiswa.mhs_nim', '=', 'tas.mhs_nim')
            ->join('dosen', 'bimbingans.dosen_nip', '=', 'dosen.dosen_nip')
            ->where('tas.ta_id', '=', $ta_id)
            ->where('dosen.email', $email)
            ->select('bimbingan_log.*', 'mahasiswa.mhs_nim', 'bimbingans.urutan') // Include mhs_nim in the results
            ->get();
            // dd($log);
        return $log;

    }
}