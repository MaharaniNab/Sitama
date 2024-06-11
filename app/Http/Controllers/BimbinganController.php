<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\BimbinganLog;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\SyaratSidang;
use App\Models\Ta;
use App\Models\KodeProdi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BimbinganController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read_bimbingan')->only('index', 'show', 'lihat');
        $this->middleware('permission:create_bimbingan')->only('create', 'store');
        $this->middleware('permission:update_bimbingan')->only('edit', 'tambah', 'update');
        $this->middleware('permission:delete_bimbingan')->only('destroy');
    }

    public function index(Request $request)
{
    $bimbingans = Bimbingan::all();
    $tas = Ta::all();

    // Mengambil daftar nama dosen
    $dosen = Dosen::pluck('dosen_nama', 'dosen_nip');

    // Mulai query untuk ta_mahasiswa2
    $ta_mahasiswa2 = Bimbingan::ta_mahasiswa2();
    $log = BimbinganLog::log();

    // Filter berdasarkan tahun akademik jika ada
    if ($request->filled('tahun_akademik')) {
        $ta_mahasiswa2->where('tas.tahun_akademik', $request->input('tahun_akademik'));
    }

    // Filter berdasarkan kode prodi jika ada
    if ($request->filled('kode_prodi')) {
        $ta_mahasiswa2->where('mahasiswa.prodi_ID', $request->input('kode_prodi'));
    }

    // Filter berdasarkan dosen jika ada
    if ($request->filled('dosen')) {
        $log->where('dosen.dosen_nama', $request->input('dosen'));
    }

    $ta_mahasiswa = $ta_mahasiswa2->get();

    $mahasiswa_comp = [];
    foreach ($ta_mahasiswa as $tm) {
        $dosen_nip_array = explode(',', $tm->dosen_nip);
        $dosen_nama_array = explode('|', $tm->dosen_nama);
        $bimbingan_id_array = explode(',', $tm->bimbingan_id);

        $dosen = [];
        foreach ($dosen_nama_array as $key => $dosen_nama) {
            $dosen[] = [
                'dosen_nama' => $dosen_nama,
                'dosen_nip' => $dosen_nip_array[$key],
                'bimbingan_id' => $bimbingan_id_array[$key]
            ];
        }
        $tm->dosen = $dosen;
        $mahasiswa_comp[] = $tm;
    }

    $kode_prodi = KodeProdi::all();

    return view('bimbingan.index', compact('mahasiswa_comp', 'dosen', 'tas', 'kode_prodi'))
        ->with('selectedProdi', $request->input('kode_prodi'));
}


    public function create()
    {
        $tas = Ta::all();
        $ta_mahasiswa = Bimbingan::ta_mahasiswa();
        $dosen = Dosen::all();
        $mhs = Bimbingan::mahasiswa();

        $taIdAda = Bimbingan::pluck('ta_id')->unique()->toArray();
        // dd($taIdAda);
        return view('bimbingan.create', compact('tas', 'ta_mahasiswa', 'dosen', 'mhs', 'taIdAda'));
    }

    public function store(Request $request)
{
    // Validation rules
    $validator = Validator::make($request->all(), [
        'mhs1' => 'required', // mhs1 is required
        'mhs2' => 'nullable', // mhs2 is optional
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

    $mhs = [
        $request->mhs1,
        $request->mhs2
    ];

    // Check if the two dosen pembimbing are the same
    if ($dosenPembimbing[0] == $dosenPembimbing[1]) {
        toastr()->warning('Dosen Pembimbing Tidak Boleh Sama');
        return redirect()->route('bimbingan.create', 'bimbingan.tambah');
    }

    // Check if the two mahasiswa are the same
    if ($mhs[0] && $mhs[0] == $mhs[1]) {
        toastr()->warning('Mahasiswa Tidak Boleh Sama');
        return redirect()->route('bimbingan.create', 'bimbingan.tambah');
    }

    foreach ($mhs as $taId) {
        // Insert records only if ta_id (mhs1 or mhs2) is not null
        if ($taId) {
            foreach ($dosenPembimbing as $index => $dosenNip) {
                $insertDospem = new Bimbingan;
                $insertDospem->dosen_nip = $dosenNip;
                $insertDospem->ta_id = $taId;
                $insertDospem->urutan = $index + 1;
                $insertDospem->verified = '0';
                $insertDospem->save();
            }
        }
    }

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
            // Menggunakan transaksi untuk menjaga integritas data
            DB::transaction(function () use ($id) {
                // Hapus dulu semua entri Bimbingan terkait ta_id yang akan dihapus
                Bimbingan::where('ta_id', $id)->delete();

                // Setelah itu baru hapus entri di Ta
                Ta::findOrFail($id)->delete();
            });

            toastr()->success('Data Bimbingan berhasil dihapus.');
            return redirect()->route('bimbingan.index');
        } catch (\Throwable $th) {
            toastr()->error('Terjadi masalah pada server. Data Bimbingan gagal dihapus. Pesan Kesalahan: ' . $th->getMessage());
            return redirect()->route('bimbingan.index');
        }
    }

    public function bimblog(Request $request, $ta_id)
{
    // Mengambil detail mahasiswa berdasarkan ta_id
    $ta_mahasiswa = Ta::detailMahasiswa($ta_id);

    // Menghitung jumlah bimbingan per pembimbing
    $jumlahBimbingan1 = BimbinganLog::log()
        ->where('bimbingans.ta_id', $ta_id)
        ->where('bimbingans.urutan', 1)
        ->get();

    $jumlahBimbingan2 = BimbinganLog::log()
        ->where('bimbingans.ta_id', $ta_id)
        ->where('bimbingans.urutan', 2)
        ->get();

    // Query log bimbingan berdasarkan ta_id
    $logQuery = BimbinganLog::log()->where('bimbingans.ta_id', $ta_id);

    // Filter log bimbingan berdasarkan pembimbing jika ada
    if($request->filled('pembimbing'))
    {
        $logQuery->where('dosen.dosen_nip', $request->pembimbing);
    }

    // Mengambil log bimbingan dan mengurutkannya
    $log = $logQuery->get()->sortBy('bimbingan_log_id');

    return view('bimbingan.bimbinganLog', compact('ta_mahasiswa', 'log', 'jumlahBimbingan1', 'jumlahBimbingan2'));
}


    public function show($ta_id)
    {
        // $ta_mahasiswa = Ta::findOrFail($ta_id);
        $ta_mahasiswa = Ta::detailMahasiswa($ta_id);

        $bimbingans = Bimbingan::all();
        $tas = Ta::all();

        // Mengambil daftar nama dosen
        $dosen = Dosen::all();
        // $ta_mahasiswa = Bimbingan::ta_mahasiswa();
        // dd($ta_mahasiswa);

        $syarat = SyaratSidang::syaratMahasiswa($ta_id);

        return view('bimbingan.upload_sk', compact('bimbingans', 'dosen', 'ta_mahasiswa', 'tas', 'syarat'));
    }

    public function verify(Request $request, $id)
{
    $syaratSidang = SyaratSidang::findOrFail($id);
    $taId = $syaratSidang->ta_id;

    if ($request->has('verified')) {
        $syaratSidang->update([
            'verified' => $request->input('verified'),
        ]);
    }

    return redirect()->route('bimbingan.show', $taId);
}

}
?>
