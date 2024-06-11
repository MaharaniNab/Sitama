@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Pembimbingan</h4>
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
                        <h5 class="m-0">{{ $mahasiswa->mhs_nim . ' - ' . $mahasiswa->mhs_nama }}</h5>
                        <div class="card-tools">
                            <a href="{{ route('mhsbimbingan.index') }}" class="btn btn-tool"><i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col table-responsive">
                                <table class="table-striped table-bordered table-hover table">
                                    <thead>
                                        <th>No</th>
                                        <th>Judul Bimbingan</th>
                                        <th>Deskripsi</th>
                                        <th>Tanggal</th>
                                        <th>File</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($bimbLog as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->bimb_judul }}</td>
                                            <td>{{ $item->bimb_desc }}</td>
                                            <td>{{ date_format(date_create($item->bimb_tgl), "D, j F Y") }}</td>
                                            <td>
                                                @if (isset($item->bimb_file))
                                                <a href="{{ asset('storage/draft_bimbingan/' . $item->bimb_file) }}" target="_blank">{{ $item->bimb_file_original }}</a>
                                                @else
                                                Tidak ada lampiran
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->bimb_status == 0)
                                                <span class="badge badge-danger">Belum Diverifikasi</span>
                                                @elseif ($item->bimb_status == 1)
                                                <span class="badge badge-success">Diverifikasi</span>
                                                @else
                                                Invalid status
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                @if ($item->bimb_status == 0)
                                                <form action="{{ route('setujui-pembimbingan', $item->bimbingan_log_id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-block btn-sm btn-outline-secondary" data-toggle="tooltip" data-placement="top" title="Setujui Bimbingan" style="border: 2px solid #007bff; color: #007bff; background-color: transparent;">
                                                        Setujui Bimbingan
                                                    </button>
                                                </form>
                                                @else
                                                <button type="button" class="btn btn-block btn-sm btn-outline-secondary" disabled><i class="fas fa-cog"></i></button>
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
                        <h5 class="m-0">Jumlah Bimbingan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col col-md-4">
                                <p class="font-weight-bold">Pembimbing - {{ $mahasiswa->dosen_nama }}</p>
                            </div>
                            <div class="col">
                                <p>
                                    : {{ $jumlahBimbingan->where('bimb_status', 1)->count() }}/{{ $jumlahBimbingan->count() }}
                                    @if ($jumlahBimbingan->where('bimb_status', 1)->count() >= 8)
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