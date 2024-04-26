@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Persyaratan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bimbingan.index') }}">Home</a></li>
                    <li class="breadcrumb-item active">Detail Persyaratan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title m-0">Detail Persyaratan</h5>
                        <div class="card-tools">
                            <a href="{{ route('bimbingan.index') }}" class="btn btn-tool"><i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('bimbingan.store') }}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                                <div class="col-sm-10">
                                    <input type="text" id="nim" name="nim" class="form-control" placeholder="Masukkan NIM" style="border-bottom-width: 1px;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mahasiswa" class="col-sm-2 col-form-label">Mahasiswa</label>
                                <div class="col-sm-10">
                                    <input type="text" id="mahasiswa" name="mahasiswa" class="form-control" placeholder="Masukkan nama mahasiswa" style="border-bottom-width: 1px;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dosen_pembimbing_1" class="col-sm-2 col-form-label">Dosen Pembimbing 1</label>
                                <div class="col-sm-10">
                                    <input type="text" id="dosen_pembimbing_1" name="dosen_pembimbing_1" class="form-control" placeholder="Masukkan Dosen Pembimbing 1" style="border-bottom-width: 1px;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dosen_pembimbing_2" class="col-sm-2 col-form-label">Dosen Pembimbing 2</label>
                                <div class="col-sm-10">
                                    <input type="text" id="dosen_pembimbing_2" name="dosen_pembimbing_2" class="form-control" placeholder="Masukkan Dosen Pembimbing 2" style="border-bottom-width: 1px;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tahun_akademik" class="col-sm-2 col-form-label">Tahun Akademik</label>
                                <div class="col-sm-10">
                                    <input type="text" id="tahun_akademik" name="tahun_akademik" class="form-control" placeholder="Masukkan Tahun Akademik" style="border-bottom-width: 1px;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="judul_ta" class="col-sm-2 col-form-label">Judul TA</label>
                                <div class="col-sm-10">
                                    <input type="text" id="judul_ta" name="judul_ta" class="form-control" placeholder="Masukkan Judul TA" style="border-bottom-width: 1px;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                                <div class="col-sm-10">
                                    <input type="text" id="keterangan" name="keterangan" class="form-control" placeholder="Masukkan Keterangan" style="border-bottom-width: 1px;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <h2>Syarat Ujian TA</h2>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Syarat</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Surat Keterangan PKL</td>
                                                <td>Belum Diperiksa</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Surat Keterangan KKL</td>
                                                <td>Belum Diperiksa</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Sertifikat TOEFL</td>
                                                <td>Belum Diperiksa</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Lembar Kontrol Bimbingan</td>
                                                <td>Belum Diperiksa</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Surat Keterangan Selesai Bimbingan</td>
                                                <td>Belum Diperiksa</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Form Siap TA</td>
                                                <td>Belum Diperiksa</td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info btn-block btn-flat"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // tambahkan script JS jika diperlukan
</script>
@endpush
