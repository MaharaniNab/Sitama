<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Ta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BimbinganController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read_bimbingan')->only('index', 'show');
        $this->middleware('permission:create_bimbingan')->only('create', 'store');
        $this->middleware('permission:update_bimbingan')->only('edit', 'update');
        $this->middleware('permission:delete_bimbingan')->only('destroy');
    }

    public function index()
    {
        $bimbingans = Bimbingan::all();
        $tas = Ta::all();

        // Mengambil daftar nama dosen
        $dosen = Dosen::pluck('dosen_nama', 'dosen_nip');
        $ta_mahasiswa = Bimbingan::ta_mahasiswa();
        // dd($ta_mahasiswa);

        return view('bimbingan.index', compact('bimbingans', 'dosen', 'ta_mahasiswa', 'tas'));
    }

    public function create()
    {
        $tas = Ta::all();
        $ta_mahasiswa = Bimbingan::ta_mahasiswa();
        $dosen = Dosen::all();
        $mhs = Bimbingan::mahasiswa();
        return view('bimbingan.create', compact('tas', 'ta_mahasiswa', 'dosen', 'mhs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dosen_pembimbing_1' => 'required',
            'dosen_pembimbing_2' => 'required'
        ]);

        if ($validator->fails()) {
            toastr()->error('Data Bimbingan gagal diperbarui. Periksa kembali data Anda.');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dosenPembimbing = [
            $request->dosen_pembimbing_1,
            $request->dosen_pembimbing_2
        ];

        if ($dosenPembimbing[0] == $dosenPembimbing[1]) {
            toastr()->warning('Dosen Pembimbing Tidak Boleh Sama');
            return redirect()->route('bimbingan.create');
        }

        $insert_ta = new Ta;
        $insert_ta->fill($request->only(['mhs_nim', 'ta_judul', 'tahun_akademik']));
        $insert_ta->draft_ta = null;
        $insert_ta->verified = 0;
        $insert_ta->save();
        foreach ($dosenPembimbing as $index => $dosenNip) {
            $insertDospem = new Bimbingan;
            $insertDospem->dosen_nip = $dosenNip;
            $insertDospem->ta_id = $insert_ta->ta_id;
            $insertDospem->urutan = $index + 1;
            $insertDospem->verified = $insert_ta->verified;
            $insertDospem->save();
        }
        // dd($dosenPembimbing);

        toastr()->success('Data Bimbingan berhasil ditambahkan.');
        return redirect()->route('bimbingan.index');
    }

    public function edit($ta_id)
    {
        $ta_mahasiswa = Ta::findOrFail($ta_id);
        // dd($ta_mahasiswa);
        $dosen = Dosen::all();
        $bimbingan = Bimbingan::where('ta_id', $ta_id)
            ->orderBy('urutan')
            ->get();
        // dd($bimbingan);
        $mhs = Bimbingan::mahasiswa();
        return view('bimbingan.edit', compact('ta_mahasiswa', 'dosen', 'mhs', 'bimbingan'));
    }



    public function update(Request $request, $id)
    {
        // dd(new Bimbingan);
        $validator = Validator::make($request->all(), [
            'pembimbing_1' => 'required',
            'pembimbing_2' => 'required'
        ]);

        if ($validator->fails()) {
            toastr()->error('Data Bimbingan gagal diperbarui. Periksa kembali data Anda.');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dosenPembimbing = [
            $request->post('pembimbing_1'),
            $request->post('pembimbing_2')
        ];


        if ($dosenPembimbing[0] == $dosenPembimbing[1]) {
            toastr()->warning('Dosen Pembimbing Tidak Boleh Sama');
            return redirect()->route('bimbingan.index');
        }

        try {
            $update_ta = Ta::findOrFail($id);
            $update_ta->mhs_nim = $request->post('mhs_nim');
            $update_ta->ta_judul = $request->post('ta_judul');
            $update_ta->tahun_akademik = $request->post('tahun_akademik');
            $update_ta->update();

            foreach ($dosenPembimbing as $index => $dosenNip) {
                // Mencari entri Bimbingan yang sesuai dengan Ta yang sedang diperbarui
                $updateDospem = Bimbingan::where('ta_id', $id)->where('urutan', $index + 1)->first();
                if (!$updateDospem) {
                    // Jika entri bimbingan tidak ditemukan, buat yang baru
                    $updateDospem = new Bimbingan();
                    $updateDospem->ta_id = $id;
                    $updateDospem->urutan = $index + 1;
                    $updateDospem->verified = 0;
                }
                $updateDospem->dosen_nip = $dosenNip;
                $updateDospem->save();
            }

            toastr()->success('Data bimbingan berhasil diperbarui');
            return redirect()->route('bimbingan.index');
        } catch (\Throwable $th) {
            toastr()->error('Terjadi masalah pada server. Data Bimbingan gagal diperbarui.' . $th->getMessage());
            return redirect()->route('bimbingan.index');
        }
    }


    public function destroy($id)
    {
        try {
            // $deleted = DB::table('tas')->where('ta_id', '=', $id)->delete();
            Ta::findOrFail($id)->delete();
            Bimbingan::where('ta_id', $id)->where('urutan', 1)->firstOrFail()->delete();
            Bimbingan::where('ta_id', $id)->where('urutan', 2)->firstOrFail()->delete();
            toastr()->success('Data Bimbingan berhasil dihapus.');
            return redirect()->route('bimbingan.index');
        } catch (\Throwable $th) {
            toastr()->error('Terjadi masalah pada server. Data Bimbingan gagal dihapus. Pesan Kesalahan: ' . $th->getMessage());
            return redirect()->route('bimbingan.index');
        }
    }

    public function verify(Request $request)
    {
        $taId = $request->input('data-id');

        Ta::where('ta_id', $taId)->update(['verified' => 1]);
        Bimbingan::where('ta_id', $taId)->update(['verified' => 1]);

        return redirect()->route('bimbingan.index')->with('success', 'Verifikasi berhasil.');

        // try {
        //     $ta = Ta::findOrFail($taId);
        //     $ta->update(['verified' => 1]);

        //     return response()->json(['success' => true]);
        // } catch (\Throwable $th) {
        //     return response()->json(['success' => false]);
        // }
    }

    // Menampilkan form upload SK
    public function show($ta_id)
    {
        // $ta_mahasiswa = Ta::findOrFail($ta_id);
        $ta_mahasiswa = Ta::detailMahasiswa($ta_id);

        // dd($ta_mahasiswa);
        $bimbingans = Bimbingan::all();
        $tas = Ta::all();

        // Mengambil daftar nama dosen
        $dosen = Dosen::all();
        // $ta_mahasiswa = Bimbingan::ta_mahasiswa();
        // dd($ta_mahasiswa);

        return view('bimbingan.upload_sk', compact('bimbingans', 'dosen', 'ta_mahasiswa', 'tas'));
    }
}
