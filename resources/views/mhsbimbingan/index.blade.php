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
                <h1 class="m-0">Data Mahasiswa Bimbingan Tugas Akhir</h1>
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
                        <h5 class="m-0">Data Mahasiswa Bimbingan Tugas Akhir</h5>
                    </div>
                    <div class="card-body">
                        <div id="datatable-container">
                            <table id="datatable-bjir" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">NIM</th>
                                        <th class="text-center">Mahasiswa</th>
                                        <th class="text-center">Judul TA</th>
                                        <th class="text-center">Tahun Akademik</th>
                                        <th class="text-center">Sebagai</th>
                                        <th class="text-center">Persetujuan Sidang Akhir</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ta_mahasiswa as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item['mhs_nim'] }}</td>
                                            <td class="text-center">{{ $item['mhs_nama'] }}</td>
                                            <td class="text-center">{{ $item['ta_judul'] }}</td>
                                            <td class="text-center">{{ $item['tahun_akademik'] }}</td>
                                            <td class="text-center">
                                                @if(isset($item['dosen']))
                                                    @foreach ($item['dosen'] as $dosen)
                                                        {{ $dosen['urutan'] == 1 ? 'Pembimbing 1' : ($dosen['urutan'] == 2 ? 'Pembimbing 2' : 'Tidak diketahui') }}
                                                        @if (!$loop->last) <br> @endif
                                                    @endforeach
                                                @else
                                                    Tidak diketahui
                                                @endif
                                            </td>
                                            <td class="text-center" id="sidang-akhir-{{ $item['ta_id'] }}">
                                                @if (isset($item['verified']) && $item['verified'] == 1)
                                                    <i class="fas fa-check-circle text-success"></i>
                                                @else
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Aksi
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="{{ route('mhsbimbingan.pembimbingan', $item['ta_id']) }}">Lihat Pembimbingan</a>
                                                        <form action="{{ route('setujui.sidang.akhir', $item['ta_id']) }}" method="POST">
                                                            @csrf
                                                            <button class="dropdown-item">Setujui Sidang Akhir</button>
                                                        </form>
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
</div>

<script>
    function setujuiSidangAkhir(ta_id) {
        fetch(/setujui-sidang-akhir/,{ta_id}, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                // Ubah ikon menjadi centang
                document.getElementById(sidang-akhir-{ta_id}).innerHTML = '<i class="fas fa-check-circle text-success"></i>';
            } else {
                alert('Gagal mengubah status.');
            }
        });
    }
</script>
@endsection

@push('js')
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Check if DataTable is already initialized
        if (!$.fn.DataTable.isDataTable('#datatable-bjir')) {
            $('#datatable-bjir').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#datatable-bjir_wrapper .col-md-6:eq(0)');
        }
    });
</script>
@endpush