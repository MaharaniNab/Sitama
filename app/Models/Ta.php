<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ta extends Model
{
    protected $table = 'tas';
    protected $primaryKey = 'ta_id';
    protected $fillable = ['mhs_nim', 'ta_judul', 'draft_ta', 'verified', 'tahun_akademik',];
    public $incrementing = true;
    public $timestamps = false;

    // Relationship with Mahasiswa model
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mhs_nim');
    }

    // Relationship with Dosen model through bimbingans
    public function dosen()
    {
        return $this->belongsToMany(Dosen::class, 'bimbingans', 'ta_id', 'dosen_nip')
                    ->withPivot('urutan')
                    ->orderBy('bimbingans.urutan');
    }

    // Relationship with KodeProdi model
    public function kode_prodi()
    {
        return $this->belongsTo(KodeProdi::class, 'prodi_ID', 'prodi_ID');
    }

    // Static method to fetch TA data along with Mahasiswa details
    public static function ta_mahasiswa()
    {
        return DB::table('tas')
            ->join('mahasiswa', 'mahasiswa.mhs_nim', '=', 'tas.mhs_nim')
            ->select('tas.*', 'mahasiswa.mhs_nim', 'mahasiswa.nama_id')
            ->orderBy('mahasiswa.mhs_nim', 'asc')
            ->get();
    }

    // Static method to fetch detailed Mahasiswa data
    public static function detailMahasiswa($ta_id)
    {
        $ta_mahasiswa = DB::table('tas')
            ->join('mahasiswa', 'mahasiswa.mhs_nim', '=', 'tas.mhs_nim')
            ->leftJoin('bimbingans', 'bimbingans.ta_id', '=', 'tas.ta_id')
            ->leftJoin('dosen', 'dosen.dosen_nip', '=', 'bimbingans.dosen_nip')
            ->leftJoin('ta_sidang', 'ta_sidang.ta_id', '=', 'tas.ta_id')
            ->select(
                'tas.ta_id',
                'tas.ta_judul',
                'tas.verified',
                'tas.tahun_akademik',
                'mahasiswa.mhs_nim',
                'mahasiswa.mhs_nama',
                'ta_sidang.judul_final',
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
                'mahasiswa.mhs_nama',
                'ta_sidang.judul_final'
            )
            ->where('tas.ta_id', '=', $ta_id)
            ->first();

        if ($ta_mahasiswa) {
            $dosen_nip_array = explode(',', $ta_mahasiswa->dosen_nip);
            $dosen_nama_array = explode('|', $ta_mahasiswa->dosen_nama);
            $bimbingan_id_array = explode(',', $ta_mahasiswa->bimbingan_id);

            $dosen = [];
            foreach ($dosen_nama_array as $key => $dosen_nama) {
                $dosen[] = [
                    'dosen_nama' => $dosen_nama,
                    'dosen_nip' => $dosen_nip_array[$key] ?? null,
                    'bimbingan_id' => $bimbingan_id_array[$key] ?? null
                ];
            }
            $ta_mahasiswa->dosen = $dosen;
        }

        return $ta_mahasiswa;
    }

    // Static method to fetch TaSidang data with necessary relations
    public static function TaSidang()
    {
        $taSidang = DB::table('ta_sidang')
            ->join('tas', 'ta_sidang.ta_id', '=', 'tas.ta_id')
            ->join('jadwal_sidang', 'ta_sidang.jadwal_id', '=', 'jadwal_sidang.jadwal_id')
            ->join('sesi_ta', 'jadwal_sidang.sesi_id', '=', 'sesi_ta.sesi_id')
            ->join('ruangan_ta', 'jadwal_sidang.ruangan_id', '=', 'ruangan_ta.ruangan_id')
            ->join('mahasiswa', 'tas.mhs_nim', '=', 'mahasiswa.mhs_nim')
            ->leftJoin('dosen', 'ta_sidang.dosen_nip', '=', 'dosen.dosen_nip')
            ->leftJoin('bimbingans as b1', function ($join) {
                $join->on('tas.ta_id', '=', 'b1.ta_id')
                     ->where('b1.urutan', '=', 1);
            })
            ->leftJoin('dosen as dosen_1', 'dosen_1.dosen_nip', '=', 'b1.dosen_nip')
            ->leftJoin('bimbingans as b2', function ($join) {
                $join->on('tas.ta_id', '=', 'b2.ta_id')
                     ->where('b2.urutan', '=', 2);
            })
            ->leftJoin('dosen as dosen_2', 'dosen_2.dosen_nip', '=', 'b2.dosen_nip')
            ->leftJoin('penilaian_penguji as p1', function ($join) {
                $join->on('ta_sidang.ta_sidang_id', '=', 'p1.ta_sidang_id')
                     ->where('p1.urutan', '=', 1);
            })
            ->leftJoin('dosen as penguji_1', 'penguji_1.dosen_nip', '=', 'p1.dosen_nip')
            ->leftJoin('penilaian_penguji as p2', function ($join) {
                $join->on('ta_sidang.ta_sidang_id', '=', 'p2.ta_sidang_id')
                     ->where('p2.urutan', '=', 2);
            })
            ->leftJoin('dosen as penguji_2', 'penguji_2.dosen_nip', '=', 'p2.dosen_nip')
            ->leftJoin('penilaian_penguji as p3', function ($join) {
                $join->on('ta_sidang.ta_sidang_id', '=', 'p3.ta_sidang_id')
                     ->where('p3.urutan', '=', 3);
            })
            ->leftJoin('dosen as penguji_3', 'penguji_3.dosen_nip', '=', 'p3.dosen_nip')
            ->select(
                'ta_sidang.*',
                'tas.mhs_nim',
                'mahasiswa.mhs_nama',
                'jadwal_sidang.tgl_sidang',
                'jadwal_sidang.sesi_id',
                'sesi_ta.sesi_waktu_mulai',
                'sesi_ta.sesi_waktu_selesai',
                'sesi_ta.sesi_nama',
                'ruangan_ta.ruangan_nama',
                'dosen_1.dosen_nama as nama_dosen_1',
                'dosen_2.dosen_nama as nama_dosen_2',
                'penguji_1.dosen_nama as nama_penguji_1',
                'penguji_2.dosen_nama as nama_penguji_2',
                'penguji_3.dosen_nama as nama_penguji_3',
                'dosen.dosen_nama as sekretaris',
                'ta_sidang.verified'
            )
            ->orderBy('mahasiswa.mhs_nim', 'asc')
            ->get();
            // dd($taSidang);

        return $taSidang;
    }

    // Static method to fetch additional TaSidang data
    public static function taSidang2()
    {
        $taSidang = DB::table('ta_sidang')
            ->Join('tas', 'tas.ta_id', '=', 'ta_sidang.ta_id')
            ->Join('mahasiswa', 'tas.mhs_nim', '=', 'mahasiswa.mhs_nim')
            ->Join('kode_prodi', 'kode_prodi.prodi_ID', '=', 'mahasiswa.prodi_ID')
            ->Join('bimbingans', 'tas.ta_id', '=', 'bimbingans.ta_id')
            ->leftJoin('penilaian_penguji', 'penilaian_penguji.ta_sidang_id', '=', 'ta_sidang.ta_sidang_id')
            ->leftJoin('dosen', 'bimbingans.dosen_nip', '=', 'dosen.dosen_nip')
            ->leftJoin('dosen as dosen_sekre', 'ta_sidang.dosen_nip', '=', 'dosen_sekre.dosen_nip')
            ->leftJoin('dosen as dosen_penguji', 'penilaian_penguji.dosen_nip', '=', 'dosen_penguji.dosen_nip')
            ->Join('jadwal_sidang','jadwal_sidang.jadwal_id', '=', 'ta_sidang.jadwal_id')
            ->Join('sesi_ta','sesi_ta.sesi_id', '=', 'jadwal_sidang.sesi_id')
            ->Join('ruangan_ta','ruangan_ta.ruangan_id', '=', 'jadwal_sidang.ruangan_id')
            ->select(
                'ta_sidang.ta_sidang_id',
                'ta_sidang.judul_final',
                'kode_prodi.prodi_ID',
                'kode_prodi.program_studi',
                'ta_sidang.nilai_akhir',
                'ta_sidang.status_lulus',
                'ta_sidang.verified',
                'mahasiswa.mhs_nim',
                'mahasiswa.mhs_nama',
                'tas.tahun_akademik',
                'tas.ta_id',
                'tas.ta_judul',
                'jadwal_sidang.tgl_sidang',
                'sesi_ta.sesi_nama',
                'sesi_ta.sesi_waktu_mulai',
                'sesi_ta.sesi_waktu_selesai',
                'ruangan_ta.ruangan_nama',
                'dosen_sekre.dosen_nama as sekre',
                DB::raw('GROUP_CONCAT(distinct(bimbingans.dosen_nip) ORDER BY bimbingans.bimbingan_id) as dosen_nip'),
                DB::raw('GROUP_CONCAT(distinct(dosen.dosen_nama) ORDER BY bimbingans.bimbingan_id SEPARATOR "|") as dosen_nama'),
                DB::raw('GROUP_CONCAT(distinct(bimbingans.bimbingan_id) ORDER BY bimbingans.bimbingan_id) as bimbingan_id'),
                DB::raw('GROUP_CONCAT(distinct(penilaian_penguji.dosen_nip) ORDER BY penilaian_penguji.urutan) as dosen_nip_penguji'),
                DB::raw('GROUP_CONCAT(distinct(dosen_penguji.dosen_nama) ORDER BY penilaian_penguji.urutan SEPARATOR "|") as penguji_nama'),
                DB::raw('GROUP_CONCAT(distinct(penilaian_penguji.urutan) ORDER BY penilaian_penguji.urutan) as urutan')
            )
            ->groupBy(
                'ta_sidang.ta_sidang_id',
                'ta_sidang.judul_final',
                'kode_prodi.prodi_ID',
                'kode_prodi.program_studi',
                'ta_sidang.nilai_akhir',
                'ta_sidang.status_lulus',
                'ta_sidang.verified',
                'mahasiswa.mhs_nim',
                'mahasiswa.mhs_nama',
                'tas.tahun_akademik',
                'tas.ta_id',
                'tas.ta_judul',
                'jadwal_sidang.tgl_sidang',
                'sesi_ta.sesi_nama',
                'sesi_ta.sesi_waktu_mulai',
                'sesi_ta.sesi_waktu_selesai',
                'ruangan_ta.ruangan_nama',
                'sekre'
            )
            ->get();

        $array_dosen = [];
        foreach($taSidang as $mahasiswa) {
            $dosen_nip_array = explode(',', $mahasiswa->dosen_nip);
            $dosen_nama_array = explode('|', $mahasiswa->dosen_nama);
            $bimbingan_id_array = explode(',', $mahasiswa->bimbingan_id);
            $dosen_nip_penguji_array = explode(',', $mahasiswa->dosen_nip_penguji);
            $penguji_nama_array = explode('|', $mahasiswa->penguji_nama);
            $urutan_array = explode(',', $mahasiswa->urutan);

            $dosen = [];
            foreach ($dosen_nama_array as $key => $dosen_nama) {
                $dosen_nip = isset($dosen_nip_array[$key]) ? $dosen_nip_array[$key] : null;
                $bimbingan_id = isset($bimbingan_id_array[$key]) ? $bimbingan_id_array[$key] : null;
                $dosen[] = [
                    'dosen_nama' => $dosen_nama,
                    'dosen_nip' => $dosen_nip,
                    'bimbingan_id' => $bimbingan_id,
                ];
            }

            $penguji = [];
            foreach ($penguji_nama_array as $key => $penguji_nama) {
                $dosen_nip_penguji = isset($dosen_nip_penguji_array[$key]) ? $dosen_nip_penguji_array[$key] : null;
                $urutan = isset($urutan_array[$key]) ? $urutan_array[$key] : null;
                $penguji[] = [
                    'dosen_nip_penguji' => $dosen_nip_penguji,
                    'penguji_nama' => $penguji_nama,
                    'urutan' => $urutan,
                ];
                // dd($penguji);
            }

            $mahasiswa->dosen = $dosen;
            $mahasiswa->penguji = $penguji;

            if (!empty($mahasiswa->sekre) && !empty($penguji) && empty($mahasiswa->nilai_akhir)) {
                $mahasiswa->status = '1';
            } elseif (!empty($mahasiswa->sekre) && !empty($penguji) && !empty($mahasiswa->nilai_akhir)) {
                $mahasiswa->status = '2';
            } else {
                $mahasiswa->status = '0';
            }

            $array_dosen[] = $mahasiswa;
        }

        return $array_dosen;
    }
}
?>
