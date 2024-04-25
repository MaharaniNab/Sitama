@extends('layouts.app')

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-uppercase">Daftar Sidang TA</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ta.index') }}">Home</a></li>
                    <li class="breadcrumb-item active">Daftar Sidang TA</li>
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
                        <h5 class="m-0">Daftar Sidang TA</h5>
                        <div class="card-tools">
                            <a href="{{ route('ta.create') }}" class="btn btn-tool"><i class="fas fa-plus-circle"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-inline mb-3">

                        </div>
                        <table id="datatable-main" class="table table-bordered table-striped text-sm">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Mahasiswa</th>
                                    <th scope="col">Waktu</th>
                                    <th scope="col">Tempat</th>
                                    <th scope="col">Dosen Pembimbing</th>
                                    <th scope="col">Penguji</th>
                                    <th scope="col">Nilai Dosen Pembimbing</th>
                                    <th scope="col">Nilai Dosen Penguji</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ta_mahasiswa as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->mhs_nama }}</td> 
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>


                                    
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
@endsection