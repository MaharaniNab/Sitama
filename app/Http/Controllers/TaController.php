<?php

namespace App\Http\Controllers;

use App\Models\Ta;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Bimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaController extends Controller
{
    public function index()
    {
        // Mengambil data dari tabel ta_sidang beserta relasi dengan mahasiswa dan jadwalSidang
        $mahasiswa = Mahasiswa::all(); // Mengambil data mahasiswa dari database atau sumber lainnya
        $ta_mahasiswa = Bimbingan::ta_mahasiswa();
        // var_dump($ta_mahasiswa);
        // Mengambil daftar nama dosen
        $dosen = Dosen::pluck('dosen_nama', 'dosen_nip');

        // Mengirim data ke tampilan
        return view('ta.index', compact('ta_mahasiswa', 'dosen', 'mahasiswa'));
    }

    
    public function create()
    {
        $tas = Ta::all();
        return view('ta.create', compact('tas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Sesuaikan dengan kebutuhan validasi untuk menyimpan data ta
        ]);

        if ($validator->fails()) {
            toastr()->error('Gagal menambahkan ta. Periksa kembali data Anda.');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Simpan data ta ke database
            toastr()->success('Data ta berhasil ditambahkan.');
            return redirect()->route('ta.index');
        } catch (\Throwable $th) {
            toastr()->error('Terjadi masalah pada server. Data ta gagal ditambahkan.');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $tas = Ta::findOrFail($id);
        return view('ta.edit', compact('ta'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // Sesuaikan dengan kebutuhan validasi untuk memperbarui data ta
        ]);

        if ($validator->fails()) {
            toastr()->error('Gagal memperbarui ta. Periksa kembali data Anda.');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $ta = Ta::findOrFail($id);
            // Perbarui data ta di database
            toastr()->success('Data ta berhasil diperbarui.');
            return redirect()->route('ta.index');
        } catch (\Throwable $th) {
            toastr()->error('Terjadi masalah pada server. Data ta gagal diperbarui.');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $ta = Ta::findOrFail($id);
            $ta->delete();

            toastr()->success('Data ta berhasil dihapus.');
            return redirect()->route('ta.index');
        } catch (\Throwable $th) {
            toastr()->error('Terjadi masalah pada server. Data ta gagal dihapus.');
            return redirect()->back();
        }
    }
}
