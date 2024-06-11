<?php

namespace App\Http\Controllers;

use App\Models\BimbinganDosen;
use Illuminate\Http\Request;
use App\Models\MahasiswaBimbingan;
use Illuminate\Support\Facades\Auth;
use App\Models\BimbinganLogDosen;
use App\Models\Ta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class MahasiswaBimbinganController extends Controller
{
    public function index()
    {
        $ta_mahasiswa = MahasiswaBimbingan::ta_mahasiswa();
        // dd($ta_mahasiswa);
        return view('mhsbimbingan.index', compact('ta_mahasiswa'));
    }

    public function view($id)
    {
        $bimbLog = BimbinganLogDosen::findOrFail($id);

        $taId = $bimbLog
        ->join('bimbingans', 'bimbingans.bimbingan_id', '=', 'bimbingan_log.bimbingan_id')
        ->select('bimbingans.ta_id')
        ->first();
        $mahasiswa = BimbinganDosen::ta_mahasiswa2()->where('tas.ta_id', $taId->ta_id)->first();
  
        $dosen_nip_array = explode(',', $mahasiswa->dosen_nip);
        $dosen_nama_array = explode('|', $mahasiswa->dosen_nama);
        $bimbingan_id_array = explode(',', $mahasiswa->bimbingan_id);

        $dosen = [];
        foreach ($dosen_nama_array as $key => $dosen_nama) {
            $dosen[] = [
                'dosen_nama' => $dosen_nama,
                'dosen_nip' => $dosen_nip_array[$key],
                'bimbingan_id' => $bimbingan_id_array[$key]
            ];
        }
        $mahasiswa->dosen = $dosen;

        return view('mhsbimbingan.show', compact('bimbLog', 'mahasiswa'));
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'judul' => 'required',
    //         'desk' => 'required',
    //         'tgl' => 'required',
    //         'pembimbing' => 'required',
    //         'draft' => 'nullable|mimes:pdf|max:2048'
    //     ]);

    //     if ($validator->fails()) {
    //         toastr()->error('Bimbingan gagal ditambah </br> Periksa kembali data anda');
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     };


    //     try {
    //         if ($request->hasFile('draft')) {
    //             $draft = $request->file('draft');
    //             $nama_file = date('Ymdhis') . '.' . $draft->getClientOriginalExtension();
    //             $draft->storeAs('public/draft_ta', $nama_file);
    //             $nama_file_original = $draft->getClientOriginalName();
    //         } else {
    //             $nama_file = null;
    //             $nama_file_original = null;
    //         }

    //         BimbinganLog::insert(
    //             [
    //                 'bimbingan_id' => $request->pembimbing,
    //                 'bimb_tgl' => $request->tgl,
    //                 'bimb_judul' => $request->judul,
    //                 'bimb_desc' => $request->desk,
    //                 'bimb_file_original' => $nama_file_original,
    //                 'bimb_file' => $nama_file,
    //                 'bimb_status' => 0,
    //             ]
    //         );
    //         toastr()->success('Bimbingan berhasil disimpan');
    //         return redirect()->route('mhsbimbingan.index');
    //     } catch (\Throwable $th) {
    //         toastr()->warning('Terdapat masalah diserver' . $th->getMessage());
    //         return redirect()->route('mhsbimbingan.index');
    //     }
    // }

    // public function show($id)
    // {
    //     $bimbLog = BimbinganLog::findorfail($id);
    //     $idUser = Auth::user()->id;
    //     $mahasiswa = Bimbingan::mahasiswa($idUser);

    //     return view('mhsbimbingan.show', compact('mahasiswa', 'bimbLog'));
    // }

    // public function edit($id)
    // {
    //     $bimbLog = BimbinganLog::findorfail($id);
    //     // dd($bimbLog);
    //     $idUser = Auth::user()->id;
    //     $ta_mahasiswa = Bimbingan::ta_mahasiswa($idUser);

    //     if ($bimbLog->bimb_status == 0) {
    //         return view('mhsbimbingan.edit', compact('ta_mahasiswa', 'bimbLog'));
    //     } else {
    //         toastr()->warning('Sudah di verifikasi');
    //         return redirect()->route('mhsbimbingan.index');
    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'judul' => 'required',
    //         'desk' => 'required',
    //         'tgl' => 'required',
    //         'pembimbing' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         toastr()->error('Bimbingan gagal diupdate </br> Periksa kembali data anda');
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     };

    //     try {
    //         $bimbLog = BimbinganLog::findOrFail($id);
    //         $bimbLog->bimbingan_id = $request->post('pembimbing');
    //         $bimbLog->bimb_judul = $request->post('judul');
    //         $bimbLog->bimb_desc = $request->post('desk');
    //         $bimbLog->bimb_tgl = $request->post('tgl');

    //         if ($request->hasFile('draft')) {
    //             $draft = $request->file('draft');
    //             $nama_file = date('Ymdhis') . '.' . $draft->getClientOriginalExtension();
    //             $draft->storeAs('public/draft_ta', $nama_file);
    //             $bimbLog->bimb_file = $nama_file;
    //             $bimbLog->bimb_file_original = $draft->getClientOriginalName();
    //         }

    //         $bimbLog->update();
    //         toastr()->success('Bimbingan berhasil diupdate');
    //         return redirect()->route('mhsbimbingan.index');
    //     } catch (\Throwable $th) {
    //         toastr()->warning('Terdapat masalah diserver' . $th->getMessage());
    //         return redirect()->route('mhsbimbingan.index');
    //     }
    // }

    // public function destroy($id)
    // {
    //     try {
    //         // DB::table('bimbingan_log')->where('bimbingan_log_id', $id)->delete();
    //         BimbinganLog::findorfail($id)->delete();
    //         toastr()->success('Log berhasil dihapus');
    //         return redirect()->route('mhsbimbingan.index');
    //     } catch (\Throwable $th) {
    //         toastr()->warning('Terdapat masalah diserver' . $th->getMessage());
    //         return redirect()->route('mhsbimbingan.index');
    //     }
    // }

    public function pembimbingan(Request $request, $ta_id)
    {
        // Fetch BimbinganLog data
        $bimbLog = BimbinganLogDosen::BimbinganLog($ta_id);

        // Fetch the first mahasiswa data matching the given ta_id
        $mahasiswa = BimbinganDosen::ta_mahasiswa2()->where('tas.ta_id', $ta_id)->first();

        // Check if $mahasiswa is null
        if (is_null($mahasiswa)) {
            // Handle the case where no mahasiswa is found
            toastr()->warning('Mahasiswa not found for the given TA ID');
            return redirect()->route('mhsbimbingan.index');
        }

        // Proceed if $mahasiswa is not null
        $dosen_nip_array = explode(',', $mahasiswa->dosen_nip);
        $dosen_nama_array = explode('|', $mahasiswa->dosen_nama);
        $bimbingan_id_array = explode(',', $mahasiswa->bimbingan_id);

        $dosen = [];
        foreach ($dosen_nama_array as $key => $dosen_nama) {
            $dosen[] = [
                'dosen_nama' => $dosen_nama,
                'dosen_nip' => $dosen_nip_array[$key],
                'bimbingan_id' => $bimbingan_id_array[$key]
            ];
        }
        $mahasiswa->dosen = $dosen;

        $jumlahBimbingan = $bimbLog->where('bimbingan_id', $mahasiswa->bimbingan_id);
        // dd($jumlahBimbingan);

        return view('mhsbimbingan.pembimbingan', compact('bimbLog', 'mahasiswa', 'jumlahBimbingan'));
    }

    public function setujuiSidangAkhir($ta_id)
    {
        // $bimbingan = MahasiswaBimbingan::where('ta_id', $ta_id)->first();
        // if ($bimbingan) {
        //     $bimbingan->status = 'Tervalidasi';
        //     $bimbingan->save();
        //     return response()->json(['success' => true]);
        // }
        // return response()->json(['success' => false]);
        $verif = BimbinganLogDosen::BimbinganLog($ta_id)->where('bimb_status', 1)->count();
        // dd($verif);

        if($verif >= 8) {
            $taSidang = DB::table('tas')
            ->where('tas.ta_id', $ta_id)
            ->update(
                [
                    'verified' => '1'
                ],
            );
            toastr()->success('Berhasil di verifikasi');
            return redirect()->route('mhsbimbingan.index');
        } else {
            toastr()->error('Bimbingan belum terpenuhi');
            return redirect()->route('mhsbimbingan.index');
        }
        
    }

    public function setujuiPembimbingan($bimbingan_log_id)
    {
        try {
            $bimbinganLog = BimbinganLogDosen::findOrFail($bimbingan_log_id);
            $bimbinganLog->bimb_status = 1; // Mengubah status menjadi diverifikasi
            $bimbinganLog->save();

            $taId = DB::table('bimbingan_log')
                ->join('bimbingans', 'bimbingans.bimbingan_id', '=', 'bimbingan_log.bimbingan_id')
                ->select('bimbingans.ta_id')
                ->where('bimbingan_log.bimbingan_log_id', $bimbingan_log_id)
                ->first();

            if ($taId) {
                return redirect()->route('mhsbimbingan.pembimbingan', $taId->ta_id);
            } else {
                toastr()->warning('TA ID not found for the given Bimbingan Log ID');
                return redirect()->route('mhsbimbingan.index');
            }
        } catch (\Exception $e) {
            // Handle exceptions and redirect appropriately
            toastr()->error('An error occurred: ' . $e->getMessage());
            return redirect()->route('mhsbimbingan.index');
        }
    }
}