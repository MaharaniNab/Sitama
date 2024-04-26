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

        return view('bimbingan.index', compact('bimbingans', 'dosen', 'ta_mahasiswa', 'tas'));
    }

    public function create()
    {
        $tas = Ta::all();
        $ta_mahasiswa = Bimbingan::ta_mahasiswa();
        $dosen = Dosen::all();
        return view('bimbingan.create', compact('tas', 'ta_mahasiswa', 'dosen'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mhs_nim' => 'required|string',
            'mahasiswa' => 'required|string',
            'pembimbing_1' => 'required|string',
            'pembimbing_2' => 'required|string',
            'ta_judul' => 'required|string',
            'verified' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            toastr()->error('Data Bimbingan gagal ditambah. Periksa kembali data Anda.');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Bimbingan::create([
                'nim' => $request->nim,
                'mahasiswa' => $request->mahasiswa,
                'pembimbing_1' => $request->pembimbing_1,
                'pembimbing_2' => $request->pembimbing_2,
                'ta_judul' => $request->ta_judul,
                'verified_by' => $request->verified ? now() : null
            ]);

            toastr()->success('Data Bimbingan berhasil ditambahkan.');
            return redirect()->route('bimbingan.index');
        } catch (\Throwable $th) {
            toastr()->error('Terjadi masalah pada server. Data Bimbingan gagal ditambahkan.');
            return redirect()->route('bimbingan.index');
        }
    }

    public function edit($ta_id)
    {
        $ta_mahasiswa = Bimbingan::findOrFail($ta_id);
        $dosen = Dosen::all();
        return view('bimbingan.edit', compact('ta_mahasiswa', 'dosen'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|string',
            'mahasiswa' => 'required|string',
            'pembimbing_1' => 'required|string',
            'pembimbing_2' => 'required|string',
            'ta_judul' => 'required|string',
            'verified' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            toastr()->error('Data Bimbingan gagal diperbarui. Periksa kembali data Anda.');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $bimbingan = Bimbingan::findOrFail($id);
            $bimbingan->update([
                // ...
                'verifikasi' => $request->verified ? 1 : 0 // Menggunakan nilai 1 atau 0 berdasarkan pilihan verifikasi
            ]);
    
            toastr()->success('Data bimbingan berhasil disimpan');
            return redirect()->route('bimbingan.index');
        } catch (\Throwable $th) {
            toastr()->error('Terjadi masalah pada server. Data Bimbingan gagal diperbarui.');
            return redirect()->route('bimbingan.index');
        }
    }

    public function destroy($id)
    {
        try {
            $bimbingan = Bimbingan::findOrFail($id);
            $bimbingan->delete();
            toastr()->success('Data Bimbingan berhasil dihapus.');
            return redirect()->route('bimbingan.index');
        } catch (\Throwable $th) {
            toastr()->error('Terjadi masalah pada server. Data Bimbingan gagal dihapus.');
            return redirect()->route('bimbingan.index');
        }
    }
    public function verify(Request $request)
    {
        $taId = $request->input('ta_id');

        try {
            $ta = Ta::findOrFail($taId);
            $ta->update(['verified' => 1]);

            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false]);
        }
    }

    // Menampilkan form upload SK
    // Menampilkan form upload SK
    public function show()
    {
        return view('bimbingan.upload_sk');
    }
}
