@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Bimbingan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bimbingan.index') }}">Home</a></li>
                    <li class="breadcrumb-item active">Edit Bimbingan</li>
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
                        <h5 class="card-title m-0">Edit Bimbingan</h5>
                        <div class="card-tools">
                            <a href="{{ route('bimbingan.index') }}" class="btn btn-tool"><i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <form action="{{ route('bimbingan.update', $bimbingan->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label>NIM</label>
                                <input type="text" name="nim" class="form-control" value="{{ $bimbingan->nim }}">
                            </div>
                            <div class="form-group">
                                <label>Mahasiswa</label>
                                <input type="text" name="mahasiswa" class="form-control" value="{{ $bimbingan->mahasiswa }}">
                            </div>
                            <div class="form-group">
                                <label>Dosen Pembimbing 1</label>
                                <input type="text" name="pembimbing_1" class="form-control" value="{{ $bimbingan->pembimbing_1 }}">
                            </div>
                            <div class="form-group">
                                <label>Dosen Pembimbing 2</label>
                                <input type="text" name="pembimbing_2" class="form-control" value="{{ $bimbingan->pembimbing_2 }}">
                            </div>
                            <div class="form-group">
                                <label>Judul TA</label>
                                <input type="text" name="ta_judul" class="form-control" value="{{ $bimbingan->ta_judul }}">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <div class="input-group">
                                    <input type="checkbox" name="verified" data-bootstrap-switch data-off-color="danger" data-on-color="success" {{ $bimbingan->bimbingan_verified_at ? 'checked' : '' }}>
                                </div>
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
@endsection
