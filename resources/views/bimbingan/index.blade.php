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
            <div class="col-sm-6 text-uppercase">
                <h4 class="m-0">Daftar Bimbingan</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
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
                        <h5 class="m-0"></h5>
                        <div class="card-tools">
                            <a href="{{ route('bimbingan.create') }}" class="btn btn-tool"><i class="fas fa-plus-circle"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-inline mb-3">
                            <form action="{{ route('bimbingan.index') }}" method="GET">
                                <div class="input-group input-group-sm">
                                </div>
                            </form>
                        </div>
                        <table id="datatable-main" class="table table-bordered table-striped text-sm">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">NIM</th>
                                    <th scope="col">Mahasiswa</th>
                                    <th scope="col">Pembimbing</th>
                                    <th scope="col">Judul TA</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($ta_mahasiswa as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->mhs_nim }}</td> 
                                    <td>{{ $item->mhs_nama }}</td> 
                                    <td>{{ $item->mhs_nama }}</td>
                                    <td>{{ $item->ta_judul }}</td>
                                    <td id="status-{{ $item->ta_id }}">{{ $item->verified === 1 ? 'Verified' : ($item->verified === 0 ? 'Not Verified' : 'Pending') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#">Edit</a>
                                                <a class="dropdown-item" href="#">Delete</a>
                                                <a class="dropdown-item btn-verify" href="#" data-id="{{ $item->ta_id }}">Verifikasi</a>
                                            </div>
                                        </div>
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

<!-- Tambahkan skrip JavaScript -->
<script>
    $(document).ready(function() {
        $('.btn-verify').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var isVerified = confirm("Apakah Anda yakin ingin memverifikasi data ini?");
            if (isVerified) {
                // Kirim permintaan AJAX untuk memperbarui status
                $.ajax({
                    url: '/bimbingan/' + id + '/verify',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        verified: true
                    },
                    success: function(response) {
                        // Perbarui tampilan status di tabel
                        $('#status-' + id).text('Verified');
                        toastr.success('Data berhasil diverifikasi.');
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Gagal memverifikasi data.');
                    }
                });
            }
        });
    });
</script>
@endsection
