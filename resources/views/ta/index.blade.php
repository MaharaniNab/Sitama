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
                        <h5 class="m-0">Data Ujian Sidang Tugas Akhir</h5>
                    </div>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <form action="{{ route('ta.index') }}" method="GET" class="form-inline mb-3">
                            @csrf
                            <div class="input-group input-group-sm">
                                <label for="filter-akademik" class="form-label mr-2">Filter Akademik:</label>
                                <select id="filter-akademik" name="tahun_akademik" class="form-select mr-2">
                                    <option value="">Pilih Tahun Akademik</option>
                                    @for ($year = 2020; $year <= 2023; $year++)
                                        <option value="{{ $year .'/'. ($year + 1) }}" {{ request('tahun_akademik') == $year.'/'.($year + 1) ? 'selected' : '' }}>{{ $year }}/{{ $year + 1 }}</option>
                                    @endfor
                                </select>
                                <select id="filter-prodi" name="kode_prodi" class="form-select mr-2">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach($kode_prodi as $prodi)
                                        <option value="{{ $prodi->prodi_ID }}" {{ request('kode_prodi') == $prodi->prodi_ID ? 'selected' : '' }}>
                                            {{ $prodi->program_studi }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <table id="datatable-bjir" class="table table-bordered table-striped text-sm">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Mahasiswa</th>
                                    <th scope="col">Sesi TA</th>
                                    <th scope="col">Dosen Pembimbing</th>
                                    <th scope="col">Tim Penguji</th>
                                    <th scope="col">Nilai</th>
                                    <th scope="col">Status Sidang</th>
                                    <th scope="col">Status Kelulusan</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($taSidang as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <b>Nama:</b> <br> {{ $item->mhs_nama }} <br>
                                        <b>NIM:</b> <br> {{ $item->mhs_nim }}
                                    </td>
                                    <td>
                                        <div>
                                            <b>Hari dan tanggal:</b><br>
                                            {{ $item->tgl_sidang }}<br>
                                        </div>
                                        <div>
                                            <b>Waktu:</b><br>
                                            {{ $item->sesi_nama }}<br>
                                            {{ $item->sesi_waktu_mulai }} - {{ $item->sesi_waktu_selesai }}<br>
                                        </div>
                                        <div>
                                            <b>Ruangan:</b><br>
                                            {{ $item->ruangan_nama }}
                                        </div>
                                    </td>
                                    <td>
                                        <ol class="pl-3">
                                            @foreach($item->dosen as $pembimbing)
                                                <li>{{ $pembimbing['dosen_nama'] }}</li>
                                            @endforeach
                                        </ol>
                                    </td>
                                    <td>
                                        <b>Dosen Penguji:</b>
                                        <ol class="pl-3">
                                            @foreach($item->penguji as $p)
                                                <li>{{ $p['penguji_nama'] }}</li>
                                            @endforeach
                                        </ol>
                                        <br> <b>Sekretaris:</b> <br>
                                        {{ $item->sekre }}
                                    </td>
                                    <td>{{ $item->nilai_akhir }}</td>
                                    <td>
                                        @if ($item->status == '1')
                                            <span class="badge badge-warning">Sudah Terjadwal</span>
                                        @elseif ($item->status == '2')
                                            <span class="badge badge-success">Sudah terlaksana</span>
                                        @else
                                            <span class="badge badge-danger">Belum Dijadwalkan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status_lulus == '1')
                                            <span class="badge badge-warning">Lulus dengan Revisi</span>
                                        @elseif ($item->status_lulus == '2')
                                            <span class="badge badge-success">Lulus</span>
                                        @elseif ($item->status_lulus == '0')
                                            <span class="badge badge-danger">Tidak Lulus</span>
                                        @else
                                            <span class="">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                @if ($item->status != '2')
                                                    <a class="dropdown-item" href="{{ route('ta.editPenguji', $item->ta_sidang_id) }}">
                                                        <i class="fas fa-users-cog"></i> Plot Tim Penguji
                                                    </a>
                                                @endif
                                                <a class="dropdown-item" href="{{ route('ta.show', $item->ta_sidang_id) }}">
                                                    <i class="fas fa-file-alt"></i> Detail Nilai
                                                </a>
                                                @if ($item->status != '2')
                                                    <form method="POST" action="{{ route('ta.destroy', $item->ta_sidang_id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item confirm-button">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                            @if ($item->status_lulus != '2')
                                            <div class="dropdown" style="display: inline;">
                                                <button class="btn btn-sm btn-outline-secondary rounded-circle btn-tooltip dropdown-toggle" type="button" id="dropdownMenuButton-{{ $item->ta_sidang_id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user-graduate"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{ $item->ta_sidang_id }}">
                                                    <form id="statusForm-{{ $item->ta_sidang_id }}" method="POST" action="{{ route('updateOrInsertStatusLulus', $item->ta_sidang_id) }}">
                                                        @csrf
                                                        <input type="hidden" id="status_lulus-{{ $item->ta_sidang_id }}" name="status_lulus" value="">
                                                    </form>
                                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); confirmAndSubmit({{ $item->ta_sidang_id }}, '0', 'Tidak Lulus');">Tidak Lulus</a>
                                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); confirmAndSubmit({{ $item->ta_sidang_id }}, '1', 'Lulus dengan Revisi');">Lulus dengan Revisi</a>
                                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); confirmAndSubmit({{ $item->ta_sidang_id }}, '2', 'Lulus');">Lulus</a>
                                                </div>
                                            @endif   
                                            </div>
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
@endsection

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

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
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: 'Export Tabel ke Excel',
                        exportOptions: {
                            columns: [0, 1, 5] // Indices of the columns you want to export
                        }
                    }
                ]
            }).buttons().container().appendTo('#datatable-bjir_wrapper .col-md-6:eq(0)');
        }
    });

    // JavaScript to submit form when an option is selected
    document.getElementById('statusSelect').addEventListener('change', function() {
        document.getElementById('statusForm').submit();
    });

    function confirmAndSubmit(id, value, status) {
        if (confirm('Apakah Anda yakin ingin mengubah status kelulusan menjadi "' + status + '"?')) {
            document.getElementById('status_lulus-' + id).value = value;
            document.getElementById('statusForm-' + id).submit();
        }
    }
</script>
@endpush
