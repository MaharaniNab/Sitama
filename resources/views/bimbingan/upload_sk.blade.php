@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title m-0">Detail Persyaratan</h5>
                            <div class="card-tools">
                                <a href="{{ route('bimbingan.index') }}" class="btn btn-tool"><i
                                        class="fas fa-arrow-left"></i></a>
                            </div>
                        </div>
                        {{-- {{dd($ta_mahasiswa)}} --}}
                        <div class="card-body">
                            <div class="row">
                                <div class="col col-md-4">
                                    <p class="font-weight-bold">NIM</p>
                                </div>
                                <div class="col">
                                    <p>: {{ $ta_mahasiswa->mhs_nim }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-4">
                                    <p class="font-weight-bold">Nama Mahasiswa</p>
                                </div>
                                <div class="col">
                                    <p>: {{ $ta_mahasiswa->mhs_nama }}</p>
                                </div>
                            </div>
                            @foreach ($ta_mahasiswa->dosen as $pembimbing)
                                <div class="row">
                                    <div class="col col-md-4">
                                        <p class="font-weight-bold">Dosen Pembimbing {{ $loop->iteration }}</p>
                                    </div>
                                    <div class="col">
                                        <p>: {{ $pembimbing['dosen_nama'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col col-md-4">
                                    <p class="font-weight-bold">Tahun Akademik</p>
                                </div>
                                <div class="col">
                                    <p>: {{ $ta_mahasiswa->tahun_akademik }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-4">
                                    <p class="font-weight-bold">Judul TA</p>
                                </div>
                                <div class="col">
                                    <p>: {{ $ta_mahasiswa->ta_judul }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">
                                Syarat Ujian TA
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Syarat</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Surat Keterangan PKL</td>
                                                <td>Belum Diperiksa</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary">Proses</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Surat Keterangan KKL</td>
                                                <td>Belum Diperiksa</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary">Proses</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Sertifikat TOEFL</td>
                                                <td>Belum Diperiksa</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary">Proses</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Lembar Kontrol Bimbingan</td>
                                                <td>Belum Diperiksa</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary">Proses</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Surat Keterangan Selesai Bimbingan</td>
                                                <td>Belum Diperiksa</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary">Proses</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Form Siap TA</td>
                                                <td>Belum Diperiksa</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary">Proses</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('js')
    <!-- Tambahkan script JavaScript jika diperlukan -->
@endpush
