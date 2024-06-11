<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BimbinganLog extends Model
{
    use HasFactory;
    protected $table = 'bimbingan_log';
    protected $primaryKey = 'bimbingan_log_id';
    protected $fillable = [
        'bimbingan_id',
        'bimb_tgl',
        'bimb_judul',
        'bimb_desc',
        'bimb_file_original',
        'bimb_file',
        'bimb_status'
    ];
    public $timestamps = false;

    public static function log()
    {
        $log = DB::table('bimbingan_log')
            ->join('bimbingans', 'bimbingans.bimbingan_id', '=', 'bimbingan_log.bimbingan_id')
            ->join('dosen', 'dosen.dosen_nip', '=', 'bimbingans.dosen_nip')
            ->select(
                'bimbingan_log.bimbingan_id',
                'bimbingan_log.bimb_tgl',
                'bimbingan_log.bimb_judul',
                'bimbingan_log.bimb_desc',
                'bimbingan_log.bimb_file_original',
                'bimbingan_log.bimb_file',
                'bimbingan_log.bimb_status',
                'bimbingans.ta_id',
                'bimbingans.dosen_nip',
                'bimbingans.urutan',
                'dosen.dosen_nama'
            );
        return $log;
    }
}
