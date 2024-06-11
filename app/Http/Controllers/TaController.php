<?php

namespace App\Http\Controllers;

use App\Models\Ta;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\KodeProdi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Toastr;


class TaController extends Controller
{
    public function index(Request $request)
{
    $dosen = Dosen::pluck('dosen_nama', 'dosen_nip');
    $mahasiswa = Mahasiswa::all();
    $kode_prodi = KodeProdi::all();
    $tas = Ta::all();

    $taSidang = Ta::taSidang2();

    $taSidangQuery = collect($taSidang);

    if ($request->filled('tahun_akademik')) {
        $taSidangQuery = $taSidangQuery->where('tahun_akademik', $request->input('tahun_akademik'));
    }

    if ($request->filled('kode_prodi')) {
        $taSidangQuery = $taSidangQuery->where('prodi_ID', $request->input('kode_prodi'));
    }

    $taSidang = $taSidangQuery->all();
    $kode_prodi = KodeProdi::all();
    
    // Render the view with the updated data
    return view('ta.index', compact('taSidang', 'dosen', 'mahasiswa', 'kode_prodi', 'tas'))
        ->with('selectedProdi', $request->input('mahasiswa.prodi'));
}


public function updateOrInsertStatusLulus(Request $request, $ta_sidang_id)
{
    // Tambahkan logging untuk debug
    \Log::info('Request Data: ', $request->all());
    \Log::info('TA Sidang ID: ', ['ta_sidang_id' => $ta_sidang_id]);

    // Validasi input
    $request->validate([
        'status_lulus' => 'nullable|integer|between:0,2',
    ]);

    // Ambil nilai status_lulus dari request
    $status_lulus = $request->input('status_lulus');

    // Mulai transaksi
    DB::beginTransaction();
    try {
        // Lakukan operasi update atau insert
        $result = DB::table('ta_sidang')->updateOrInsert(
            ['ta_sidang_id' => $ta_sidang_id], // kondisi pencarian
            ['status_lulus' => $status_lulus]  // data yang akan diupdate atau dimasukkan
        );

        // Logging hasil update
        \Log::info('UpdateOrInsert Result: ', ['result' => $result]);

        // Commit transaksi
        DB::commit();

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('ta.index')->with('success', 'Status lulus berhasil diperbarui.');
    } catch (\Exception $e) {
        // Jika terjadi kesalahan, rollback transaksi dan kembalikan pesan kesalahan
        DB::rollBack();
        \Log::error('Error updating status: ', ['error' => $e->getMessage()]);
        return redirect()->back()->withErrors(['error' => 'Gagal memperbarui status lulus', 'details' => $e->getMessage()]);
    }
}















    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                DB::table('penilaian_penguji_detail')->whereIn('dosen_nip', function ($query) use ($id) {
                    $query->select('dosen_nip')
                        ->from('penilaian_penguji')
                        ->where('ta_sidang_id', $id);
                })->delete();

                DB::table('penilaian_penguji')->where('ta_sidang_id', $id)->delete();
                DB::table('ta_sidang')->where('ta_sidang_id', $id)->delete();
            });

            toastr()->success('Data Sidang TA berhasil dihapus.');
            return redirect()->route('ta.index');
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to delete data', 'details' => $th->getMessage()], 500);
        }
    }



    public function editPenguji($taSidangId)
    {
        // Fetching necessary data
        $taSidang = DB::table('ta_sidang')
            ->where('ta_sidang_id', $taSidangId)
            ->first();

        $dosenList = DB::table('dosen')->select('dosen_nip', 'dosen_nama')->get();

        $penguji = DB::table('penilaian_penguji')
            ->where('ta_sidang_id', $taSidangId)
            ->get()
            ->keyBy('urutan');

        $sekretaris = '';
        // dd($sekretaris);
        return view('ta.penguji', compact('taSidang', 'dosenList', 'penguji', 'sekretaris'));
    }

    public function updatePenguji(Request $request, $taSidangId)
    {
        $request->validate([
            'penguji_1' => 'nullable|string',
            'penguji_2' => 'nullable|string',
            'penguji_3' => 'nullable|string',
            'sekretaris' => 'nullable|string',
        ]);

        $pembimbing = DB::table('bimbingans')
            ->join('ta_sidang', 'ta_sidang.ta_id', '=', 'bimbingans.ta_id')
            ->select(
                DB::raw('GROUP_CONCAT(distinct(bimbingans.dosen_nip) ORDER BY bimbingans.bimbingan_id) as dosen_nip')
            )
            ->where('ta_sidang.ta_sidang_id', $taSidangId)
            ->first();

        $dosen_nip_array = explode(',', $pembimbing->dosen_nip);
        $pembimbing1 = $dosen_nip_array[0];
        $pembimbing2 = $dosen_nip_array[1] ?? null;

        $penguji1 = $request->input('penguji_1');
        $penguji2 = $request->input('penguji_2');
        $penguji3 = $request->input('penguji_3');
        $sekretaris = $request->input('sekretaris');

        // Check if any penguji are the same or same as pembimbing
        if (($penguji1 && ($penguji1 == $penguji2 || $penguji1 == $penguji3 || $penguji1 == $pembimbing1 || $penguji1 == $pembimbing2)) ||
            ($penguji2 && ($penguji2 == $penguji3 || $penguji2 == $pembimbing1 || $penguji2 == $pembimbing2)) ||
            ($penguji3 && ($penguji3 == $pembimbing1 || $penguji3 == $pembimbing2))
        ) {
            toastr()->error('Penguji cannot be the same as another Penguji or Pembimbing.');
            return redirect()->route('ta.index');
        }

        // Check if sekretaris is same as any penguji or pembimbing
        if ($sekretaris && ($sekretaris == $penguji1 || $sekretaris == $penguji2 || $sekretaris == $penguji3 || $sekretaris == $pembimbing1 || $sekretaris == $pembimbing2)) {
            toastr()->error('Sekretaris tidak boleh sama dengan salah satu dari Penguji atau Pembimbing.');
            return redirect()->route('ta.index');
        }

        DB::beginTransaction();
        try {
            // Update penguji 1
            if ($penguji1) {
                DB::table('penilaian_penguji')
                    ->updateOrInsert(
                        ['ta_sidang_id' => $taSidangId, 'urutan' => 1],
                        ['dosen_nip' => $penguji1]
                    );
            }

            // Update penguji 2
            if ($penguji2) {
                DB::table('penilaian_penguji')
                    ->updateOrInsert(
                        ['ta_sidang_id' => $taSidangId, 'urutan' => 2],
                        ['dosen_nip' => $penguji2]
                    );
            }

            // Update penguji 3
            if ($penguji3) {
                DB::table('penilaian_penguji')
                    ->updateOrInsert(
                        ['ta_sidang_id' => $taSidangId, 'urutan' => 3],
                        ['dosen_nip' => $penguji3]
                    );
            }

            // Update or insert sekretaris if not same as any penguji
            if ($sekretaris) {
                DB::table('ta_sidang')
                    ->updateOrInsert(
                        ['ta_sidang_id' => $taSidangId],
                        ['dosen_nip' => $sekretaris]
                    );
                toastr()->success('Plotting Tim Penguji berhasil.');
            }

            DB::commit();
            return redirect()->route('ta.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal memperbarui penguji', 'details' => $e->getMessage()], 500);
        }
    }



    public function show()
    {
        $taSidang = Ta::TaSidang2();
        $dosen = Dosen::all();
        $mahasiswa = Mahasiswa::all();
        $tas = Ta::all();

        return view('ta.nilai', compact('taSidang', 'dosen', 'mahasiswa', 'tas', 'kode_prodi'));
    }
}
