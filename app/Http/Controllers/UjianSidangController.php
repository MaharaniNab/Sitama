<?php

namespace App\Http\Controllers;

use App\Models\DosenD;
use App\Models\TaRoleDosen;
use Illuminate\Http\Request;
use App\Models\UjianSidang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UjianSidangController extends Controller
{
    public function index()
    {
        $ta_mahasiswa = UjianSidang::ta_mahasiswa();
        Carbon ::setLocale('id');
        foreach($ta_mahasiswa as $item) {
            $item->format_tanggal = Carbon::parse( $item->tgl_sidang)->translatedFormat('l, j F Y');
        }
        //  dd($ta_mahasiswa);
        // $taSidang = UjianSidang::TaSidang();
        return view('ujian-sidang.index', compact('ta_mahasiswa'));        
        // return view('ujian-sidang.index', compact('taSidang'));

    }

    public function kelayakan($ta_id)
    {

        $ta_mahasiswa = TaRoleDosen::findOrFail($ta_id);
        $infoMhs = TaRoleDosen::detailKelayakan($ta_id);
        $nilai_pembimbing = TaRoleDosen::unsur_nilai_pembimbing()->get();
        $nilai_penguji = TaRoleDosen::unsur_nilai_penguji();
        // dd ($infoMhs);
        return view('ujian-sidang.kelayakan', compact('ta_mahasiswa', 'infoMhs', 'nilai_pembimbing'));
    }

    
    public function penguji($ta_id)
    {

        $ta_mahasiswa = TaRoleDosen::findOrFail($ta_id);
        $infoMhs = TaRoleDosen::detailKelayakan2($ta_id);
        $nilai_penguji = TaRoleDosen::unsur_nilai_penguji()->get();
        // dd ($infoMhs);
        return view('ujian-sidang.penguji', compact('ta_mahasiswa', 'infoMhs', 'nilai_penguji'));
    }

    public function storeKelayakan(Request $request, $taSidangId)
    {
        $nip = DosenD::dosenNip()->dosen_nip;

        $nilai_pembimbing = TaRoleDosen::unsur_nilai_pembimbing()->get();

        foreach($nilai_pembimbing as $index => $nilai) {

        DB::table('penilaian_pembimbing')->updateOrInsert(
            
                [
                    'ta_sidang_id' => $taSidangId, 
                    'nilai_id' => $request->nilaiId[$index], 
                    'dosen_nip' => $nip,
                ],
                [
                    'berinilai' => $request->unsur[$nilai->nilai_id]
                ]
        );
    };

    $nilaiPembimbingAll = DB::table('penilaian_pembimbing')
        ->join('unsur_nilai_pembimbing', 'unsur_nilai_pembimbing.nilai_id', '=', 'penilaian_pembimbing.nilai_id')
        ->where('penilaian_pembimbing.ta_sidang_id', $taSidangId)
        ->select(
            'penilaian_pembimbing.dosen_nip', 
            'penilaian_pembimbing.berinilai', 
            'unsur_nilai_pembimbing.bobot', 
            )
        ->get();
    
    $pembimbingJumlah = DB::table('bimbingans')
        ->join('ta_sidang', 'ta_sidang.ta_id', '=', 'bimbingans.ta_id')
        ->where('ta_sidang.ta_sidang_id', $taSidangId)
        ->count();

        $nilaiBobot = 0;
        foreach($nilaiPembimbingAll as $nilai) {
            $nilaiBobot += $nilai->berinilai * $nilai->bobot;
        }
        $rataRata = $nilaiBobot / $pembimbingJumlah;
             
        DB::table('ta_sidang')
        ->where('ta_sidang_id', $taSidangId)
        ->update(
            [
                    'nilai_pembimbing' => $rataRata,
            ],
        );

        $taSidang = DB::table('ta_sidang')
        ->where('ta_sidang_id', $taSidangId)
        ->first();

        $nilaiPembimbing = $taSidang->nilai_pembimbing ?? 0;
        $nilaiPenguji = $taSidang->nilai_penguji ?? 0;

        $nilaiAkhir = ($nilaiPembimbing + $nilaiPenguji);

        DB::table('ta_sidang')
        ->where('ta_sidang_id', $taSidangId)
        ->update(
            [
                    'nilai_akhir' => $nilaiAkhir
            ],
        );
 
        return redirect()->route('ujian-sidang.index')->with('success', 'Nilai berhasil disimpan.');
    }


    
    public function storePenguji(Request $request, $taSidangId)
    {
        $nilai_penguji = TaRoleDosen::unsur_nilai_penguji()->get();

        $nip = DosenD::dosenNip()->dosen_nip;

        foreach($nilai_penguji as $index => $nilai) {
        DB::table('penilaian_penguji_detail')->updateOrInsert(
            
                [
                    'ta_sidang_id' => $taSidangId, 
                    'nilai_id' => $request->nilaiId[$index], 
                    'dosen_nip' => $nip,
                ],
                [
                    'berinilai' => $request->unsur[$nilai->nilai_id]
                ]
        );
    };

    $nilaiPengujiAll = DB::table('penilaian_penguji_detail')
        ->join('unsur_nilai_penguji', 'unsur_nilai_penguji.nilai_id', '=', 'penilaian_penguji_detail.nilai_id')
        ->where('penilaian_penguji_detail.ta_sidang_id', $taSidangId)
        ->select(
            'penilaian_penguji_detail.dosen_nip', 
            'penilaian_penguji_detail.berinilai', 
            'unsur_nilai_penguji.bobot', 
            )
        ->get();
    
    $pengujiJumlah = DB::table('penilaian_penguji')
        ->where('penilaian_penguji.ta_sidang_id', $taSidangId)
        ->count();

        $nilaiBobot = 0;
        foreach($nilaiPengujiAll as $nilai) {
            $nilaiBobot += $nilai->berinilai * $nilai->bobot;
        }
        $rataRata = $nilaiBobot / $pengujiJumlah;
             
        DB::table('ta_sidang')
        ->where('ta_sidang_id', $taSidangId)
        ->update(
            [
                    'nilai_penguji' => $rataRata,
            ],
        );

        $taSidang = DB::table('ta_sidang')
        ->where('ta_sidang_id', $taSidangId)
        ->first();

        $nilaiPembimbing = $taSidang->nilai_pembimbing ?? 0;
        $nilaiPenguji = $taSidang->nilai_penguji ?? 0;

        $nilaiAkhir = ($nilaiPembimbing + $nilaiPenguji);

        DB::table('ta_sidang')
        ->where('ta_sidang_id', $taSidangId)
        ->update(
            [
                    'nilai_akhir' => $nilaiAkhir
            ],
        );

        return redirect()->route('ujian-sidang.index')->with('success', 'Nilai berhasil disimpan.');
    }
    public function showRevisi($ta_id)
    {
        $ta_mahasiswa = TaRoleDosen::findOrFail($ta_id);
        $revisi = TaRoleDosen::Revisi($ta_id);
        $infoMhs = TaRoleDosen::detailKelayakan($ta_id);

        // dd ($revisi);
        // dd ($infoMhs);
        return view('ujian-sidang.revisi', compact('ta_mahasiswa', 'revisi', 'infoMhs'));
    }

    public function showRevisi2($ta_id)
    {
        $ta_mahasiswa = TaRoleDosen::findOrFail($ta_id);
        $revisi = TaRoleDosen::Revisi2($ta_id);
        $infoMhs = TaRoleDosen::detailKelayakan2($ta_id);

        // dd ($infoMhs);
        return view('ujian-sidang.revisi2', compact('ta_mahasiswa', 'revisi', 'infoMhs'));
    }
}