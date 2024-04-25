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
                        <table id="datatable-main" class="table table-bordered table-striped text-sm">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">NIM</th>
                                    <th scope="col">Mahasiswa</th>
                                    <th scope="col">Nama Pembimbing</th>
                                    <th scope="col">Judul TA</th>
                                    <th scope="col">TA</th>
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
                                    <td>{{ $item->tahun_akademik }}</td>
                                    <td id="status-{{ $item->ta_id }}">{{ $item->verified == 1 ? 'Verified' : ($item->verified == 0 ? 'Not Verified' : 'Pending') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-block btn-sm btn-outline-info" data-toggle="dropdown">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                            <a class="dropdown-item" href="{{ route('bimbingan.edit', $item->ta_id) }}">Edit</a>
                                            <form method="POST" action="{{ route('bimbingan.destroy', $item->ta_id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a class="dropdown-item confirm-button" href="#">Hapus</a>
                                            </form>
                                            <div class="dropdown-divider"></div>
                                            <!-- Tambah Permission -->
                                            <a class="dropdown-item" data-toggle="modal" data-target="#modal-default{{ $item->ta_id }}" href="#">Verifikasi Data</a>
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
                // Kirim permintaan AJAX untuk memverifikasi status
                $.ajax({
                    url: '/bimbingan/' + id + '/verify',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Perbarui tampilan status di tabel
                        $('#status-' + id).text('Verified');
                        toastr.success('Data berhasil diverifikasi.');
                    },
                    error: function(xhr) {
                        toastr.error('Terjadi kesalahan! Silakan coba lagi nanti.');
                    }
                });

            }
        });

        // Periksa apakah DataTable sudah diinisialisasi sebelumnya sebelum mencoba menginisialisasinya kembali
        if (!$.fn.DataTable.isDataTable('#datatable-main')) {
            $('#datatable-main').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#datatable-main_wrapper .col-md-6:eq(0)');
        }
    });
</script>
@endpush
@endsection