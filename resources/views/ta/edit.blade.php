@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-uppercase">Edit Sidang TA</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ta.index') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('ta.edit', $ta->id) }}">Edit Sidang TA</a></li>
                    <li class="breadcrumb-item active">Edit Sidang TA</li>
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
                        <h5 class="m-0">Edit Sidang TA</h5>
                        <div class="card-tools">
                            <a href="{{ route('ta.index') }}" class="btn btn-tool"><i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('ta.update', $ta->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="mahasiswa">Mahasiswa</label>
                                <input type="text" id="mahasiswa" name="mahasiswa" class="form-control" placeholder="Nama Mahasiswa" value="{{ $ta->mahasiswa }}">
                            </div>
                            <div class="form-group">
                                <label for="waktu">Waktu</label>
                                <input type="datetime-local" id="waktu" name="waktu" class="form-control" value="{{ $ta->waktu }}">
                            </div>
                            <div class="form-group">
                                <label for="tempat">Tempat</label>
                                <input type="text" id="tempat" name="tempat" class="form-control" placeholder="Tempat Sidang" value="{{ $ta->tempat }}">
                            </div>
                            <div class="form-group">
                                <label for="dosen_pembimbing">Dosen Pembimbing</label>
                                <input type="number" id="dosen_pembimbing" name="dosen_pembimbing" class="form-control" placeholder="Dosen Pembimbing" value="{{ $ta->dosen_pembimbing }}">
                            </div>
                            <div class="form-group">
                                <label for="dosen_penguji">Dosen Penguji</label>
                                <input type="number" id="dosen_penguji" name="dosen_penguji" class="form-control" placeholder="Dosen Penguji" value="{{ $ta->dosen_penguji }}">
                            </div>
                            <div class="form-group">
                                <label for="nilai_pembimbing">Nilai Dosen Pembimbing</label>
                                <input type="number" id="nilai_pembimbing" name="nilai_pembimbing" class="form-control" placeholder="Nilai Dosen Pembimbing" value="{{ $ta->nilai_pembimbing }}">
                            </div>
                            <div class="form-group">
                                <label for="nilai_penguji">Nilai Dosen Penguji</label>
                                <input type="number" id="nilai_penguji" name="nilai_penguji" class="form-control" placeholder="Nilai Dosen Penguji" value="{{ $ta->nilai_penguji }}">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="form-control">
                                    <option value="Selesai" {{ $ta->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="Belum Selesai" {{ $ta->status == 'Belum Selesai' ? 'selected' : '' }}>Belum Selesai</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
