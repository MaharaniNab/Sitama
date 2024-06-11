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
                <h1 class="m-0">Data Ujian Sidang Tugas Akhir</h1>
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
                        <h5 class="m-2"></h5>
                    </div>
                    <div class="card-body">
                        <table id="datatable-bjir" class="table table-bordered table-striped text-sm">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">No</th>
                                    <th scope="col">Mahasiswa</th>
                                    <th scope="col">Sesi TA</th>
                                    <th scope="col">Pembimbing</th>
                                    <th scope="col">Penguji</th>
                                    <th scope="col">Sekre</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Status Kelulusan</th>
                                    <th scope="col">Nilai Akhir</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ta_mahasiswa as $item)
                                @php
                                $isPembimbing = false;
                                $isPenguji = false;
                                $userName = auth()->user()->name;

                                foreach ($item->user_dosen as $dosen) {
                                if ($dosen['user_dosen_nama'] == $userName) {
                                $isPembimbing = true;
                                break;
                                }
                                }

                                foreach ($item->user_dosen_penguji as $dosen) {
                                if ($dosen['user_dosen_nama'] == $userName) {
                                $isPenguji = true;
                                break;
                                }
                                }
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $item->mhs_nama }}
                                        <br> <b>({{ $item->mhs_nim }})</b> <br>
                                    </td>
                                    <td>
                                        <div>
                                            <b>Ruangan:</b><br>
                                            {{ $item->ruangan_nama }}
                                        </div>
                                        <div>
                                            <b>Hari dan tanggal:</b><br>
                                            {{ $item->format_tanggal }}<br>
                                        </div>
                                        <div>
                                            <b>Waktu:</b><br>
                                            {{ $item->sesi_nama }}<br>
                                            {{ date('H:i', strtotime($item->sesi_waktu_mulai)) }} - {{ date('H:i', strtotime($item->sesi_waktu_selesai)) }}<br>
                                        </div>
                                    </td>
                                    <td>
                                        <ol class="px-2">
                                            @foreach ($item->user_dosen as $dosen)
                                            @if ($dosen['user_dosen_nama'] == $userName)
                                            <li class="m-0 p-0"><b>{{ $dosen['user_dosen_nama'] }}</b></li>
                                            @else
                                            <li class="m-0 p-0">{{ $dosen['user_dosen_nama'] }}</li>
                                            @endif
                                            @endforeach
                                        </ol>
                                    </td>
                                    <td>
                                        <ol class="px-2">
                                            @foreach ($item->user_dosen_penguji as $dosen)
                                            @if ($dosen['user_dosen_nama'] == $userName)
                                            <li class="m-0 p-0"><b>{{ $dosen['user_dosen_nama'] }}</b></li>
                                            @else
                                            <li class="m-0 p-0">{{ $dosen['user_dosen_nama'] }}</li>
                                            @endif
                                            @endforeach
                                        </ol>
                                    </td>
                                    <td>{{ $item->sekre }}</td>
                                    <td>
                                            @if ($item->nilai_akhir == null)
                                            <span class="badge badge-warning">Sudah terjadwal</span>
                                            @else
                                            <span class="badge badge-success">Sudah terlaksana</span>
                                            @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($item->status_lulus == 0)
                                        <span class="badge badge-warning">Belum melaksanakan sidang</span>
                                        @elseif ($item->status_lulus == 1)
                                        <h6><span class="badge badge-success">Lulus</span></h6>
                                        @elseif ($item->status_lulus == 2)
                                        <h6><span class="badge badge-warning">Lulus dengan revisi</span></h6>
                                        @elseif ($item->status_lulus == 3)
                                        <h6><span class="badge badge-danger">Tidak lulus</span></h6>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            {{ $item->nilai_akhir}}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($isPembimbing || $isPenguji)
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Aksi
                                            </button>
                                            <div class="dropdown-menu">
                                                @if ($isPembimbing)
                                                <a class="dropdown-item" href="{{ route('ujian-sidang.kelayakan', ['ta_id' => $item->ta_id]) }}" data-toggle="tooltip" data-placement="top" title="Input Kelayakan Ujian Sidang">
                                                    <i class="fas fa-edit"></i> Input Nilai
                                                </a>
                                                <a class="dropdown-item" href="{{ route('ujian-sidang.revisi', ['ta_id' => $item->ta_id]) }}" data-toggle="tooltip" data-placement="top" title="Lihat Revisi">
                                                    <i class="fas fa-eye"></i> Lihat Revisi
                                                </a>
                                                @elseif ($isPenguji)
                                                <a class="dropdown-item" href="{{ route('ujian-sidang.penguji', ['ta_id' => $item->ta_id]) }}" data-toggle="tooltip" data-placement="top" title="Input Nilai Penguji">
                                                    <i class="fas fa-edit"></i> Input Nilai
                                                </a>
                                                <a class="dropdown-item" href="{{ route('ujian-sidang.revisi2', ['ta_id' => $item->ta_id]) }}" data-toggle="tooltip" data-placement="top" title="Lihat Revisi">
                                                    <i class="fas fa-eye"></i> Lihat Revisi
                                                </a>
                                                @endif

                                            </div>
                                        </div>
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
@endsection



@push('js')
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
        if (!$.fn.DataTable.isDataTable('#datatable-bjir')) {
            $('#datatable-bjir').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["excel", "pdf", ]
            }).buttons().container().appendTo('#datatable-bjir_wrapper .col-md-6:eq(0)');
        }
    });
</script>
@endpush