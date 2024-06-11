@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Plotting Tim Penguji</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bimbingan.index') }}">Home</a></li>
                    <li class="breadcrumb-item active">Plotting Tim Penguji</li>
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
                        <h5 class="card-title m-0">Plotting Tim Penguji</h5>
                        <div class="card-tools">
                            <a href="{{ route('ta.index') }}" class="btn btn-tool"><i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <form action="{{ route('ta.updatePenguji', $taSidang->ta_sidang_id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="penguji_1">Penguji 1</label>
                                <select name="penguji_1" id="penguji_1" class="form-control">
                                    <option value="">Select Penguji 1</option>
                                    @foreach($dosenList as $dosen)
                                        <option value="{{ $dosen->dosen_nip }}" {{ isset($penguji[1]) && $penguji[1]->dosen_nip == $dosen->dosen_nip ? 'selected' : '' }}>
                                            {{ $dosen->dosen_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="penguji_2">Penguji 2</label>
                                <select name="penguji_2" id="penguji_2" class="form-control">
                                    <option value="">Select Penguji 2</option>
                                    @foreach($dosenList as $dosen)
                                        <option value="{{ $dosen->dosen_nip }}" {{ isset($penguji[2]) && $penguji[2]->dosen_nip == $dosen->dosen_nip ? 'selected' : '' }}>
                                            {{ $dosen->dosen_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="penguji_3">Penguji 3</label>
                                <select name="penguji_3" id="penguji_3" class="form-control">
                                    <option value="">Select Penguji 3</option>
                                    @foreach($dosenList as $dosen)
                                        <option value="{{ $dosen->dosen_nip }}" {{ isset($penguji[3]) && $penguji[3]->dosen_nip == $dosen->dosen_nip ? 'selected' : '' }}>
                                            {{ $dosen->dosen_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                    <label for="sekretaris">Sekretaris</label>
                                    <select name="sekretaris" id="sekretaris" class="form-control">
                                        <option value="">Select sekretaris</option>
                                        @foreach($dosenList as $dosen)
                                            <option value="{{ $dosen->dosen_nip }}" {{ $taSidang->dosen_nip == $dosen->dosen_nip ? 'selected' : '' }}>
                                                {{ $dosen->dosen_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update Penguji</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
