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
        $ta_mahasiswa = Bimbingan::ta_mahasiswa(); // Menggunakan method ta_mahasiswa dari model Bimbingan

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
            'mhs_nama' => 'required|string',
            'dosen_nama' => 'required|string',
            'dosen_nama' => 'required|string',
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
            Bimbingan::insert([
                'mhs_nim' => $request->mhs_nim,
                'mhs_nama' => $request->mhs_nama,
                'dosen_nama' => $request->dosen_nama,
                'dosen_nama' => $request->dosen_nama,
                'ta_judul' => $request->ta_judul,
                'verified' => $request->verified ? now() : null
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


    public function update(Request $request, $ta_id)
    {
        $validator = Validator::make($request->all(), [
            'mhs_nim' => 'required|string',
            'mhs_nama' => 'required|string',
            'dosen_nama' => 'required|string',
            'dosen_nama' => 'required|string',
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
            $bimbingan = Bimbingan::findOrFail($ta_id);
            $bimbingan->update([
                'mhs_nim' => $request->mhs_nim,
                'mhs_nama' => $request->mhs_nama,
                'dosen_nama' => $request->dosen_nama,
                'dosen_nama' => $request->dosen_nama,
                'ta_judul' => $request->ta_judul,
                'verified' => $request->verified ? 1 : 0 // Menggunakan nilai 1 atau 0 berdasarkan pilihan verifikasi
            ]);

            toastr()->success('Data bimbingan berhasil disimpan');
            return redirect()->route('bimbingan.index');
        } catch (\Throwable $th) {
            toastr()->error('Terjadi masalah pada server. Data Bimbingan gagal diperbarui.');
            return redirect()->route('bimbingan.index');
        }
    }

    public function destroy($ta_id)
    {
        try {
            $bimbingan = Bimbingan::findOrFail($ta_id);
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
}
