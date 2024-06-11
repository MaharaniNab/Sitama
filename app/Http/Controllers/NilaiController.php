<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller{
    public function show($ta_sidang_id)
{
    $penilaian = Nilai::penilaian($ta_sidang_id);
    $penguji = Nilai::penguji($ta_sidang_id);
    $pembimbing = Nilai::pembimbing($ta_sidang_id);
    
    // dd('$groupedPembimbing');
    return view('ta.nilai', compact('penilaian', 'ta_sidang_id', 'penguji', 'pembimbing'));
}

}

