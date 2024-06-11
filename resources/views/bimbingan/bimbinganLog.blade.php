@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0">Bimbingan</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right"></ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="card-tools">
                                <a href="{{ route('bimbingan.index') }}" class="btn btn-tool"><i class="fas fa-arrow-left"></i></a>
                            </div>
                        </div>
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
                                    <p class="font-weight-bold m-0">Nama Mahasiswa</p>
                                </div>
                                <div class="col">
                                    <p>: {{ $ta_mahasiswa->mhs_nama }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-4">
                                    <p class="font-weight-bold m-0">Judul Tugas Akhir</p>
                                </div>
                                <div class="col">
                                    <p>: {{ $ta_mahasiswa->ta_judul }}</p>
                                </div>
                            </div>
                            <br>
                            <form action="{{ route('bimbingan.bimblog', $ta_mahasiswa->ta_id) }}" method="GET">
                                @csrf
                                <div class="row d-flex align-items-center">
                                    <div class="col-md-4 col-12 m-md-0 mb-3">
                                        <select class="custom-select" name="pembimbing">
                                            <option value="">All Pembimbing</option>
                                            @foreach ($ta_mahasiswa->dosen as $pembimbing)
                                                <option value="{{ $pembimbing['dosen_nip'] }}" @if (request('pembimbing') == $pembimbing['dosen_nip']) selected @endif>Pembimbing {{ $loop->iteration . ' - ' . $pembimbing['dosen_nama'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-12">
                                        <button type="submit" class="btn btn-sm btn-block btn-primary">Search</button>
                                    </div>
                                </div>
                            </form>
                            <div class="card-tools">
                                <a href="{{ route('bimbingan.index') }}" class="btn btn-tool"></a>
                            </div>
                            <div class="row">
                                <div class="col table-responsive">
                                    <table class="table-striped table-bordered table-hover table">
                                        <thead>
                                            <th>No</th>
                                            <th>Dosen Pembimbing</th>
                                            <th>Judul Bimbingan</th>
                                            <th>Deskripsi</th>
                                            <th>Tanggal</th>
                                            <th>File</th>
                                            <th>Status</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($log as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->dosen_nama }}</td>
                                                    <td>{{ $item->bimb_judul }}</td>
                                                    <td>{{ $item->bimb_desc }}</td>
                                                    <td>{{ $item->bimb_tgl }}</td>
                                                    <td>
                                                        @if (isset($item->bimb_file))
                                                            <a href="{{ asset('storage/draft_ta/'. $item->bimb_file) }}">{{ $item->bimb_file }}</a>
                                                        @else
                                                            Tidak Ada Lampiran
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($item->bimb_status == 0)
                                                            <span class="badge badge-danger">Belum Diverifkasi</span>
                                                        @elseif ($item->bimb_status == 1)
                                                            <span class="badge badge-success">Diverifkasi</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
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
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title m-0">Jumlah Bimbingan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col col-md-4">
                                    <p class="font-weight-bold">Pembimbing 1 - {{ $ta_mahasiswa->dosen[0]['dosen_nama'] }}</p>
                                </div>
                                <div class="col">
                                    <p>:
                                        {{ $jumlahBimbingan1->where('bimb_status', 1)->count() . '/' . $jumlahBimbingan1->count() }}
                                        @if ($jumlahBimbingan1->where('bimb_status', 1)->count() >= 8)
                                            <span class="badge badge-success ml-1">Terpenuhi</span>
                                        @else
                                            <span class="badge badge-danger ml-1">Belum Terpenuhi</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-4">
                                    <p class="font-weight-bold m-0">Pembimbing 2 - {{ $ta_mahasiswa->dosen[1]['dosen_nama'] }}</p>
                                </div>
                                <div class="col">
                                    <p>:
                                        {{ $jumlahBimbingan2->where('bimb_status', 1)->count() . '/' . $jumlahBimbingan2->count() }}
                                        @if ($jumlahBimbingan2->where('bimb_status', 1)->count() >= 8)
                                            <span class="badge badge-success ml-1">Terpenuhi</span>
                                        @else
                                            <span class="badge badge-danger ml-1">Belum Terpenuhi</span>
                                        @endif
                                    </p>
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
    <script>
        $('.toast').toast('show')
    </script>
@endpush
