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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <form action="{{ route('bimbingan.index') }}" method="GET" class="form-inline mb-3">
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
                            <div class="card-tools" style="position: absolute; top: 10px; right: 10px;">
                                <a href="{{ route('bimbingan.create') }}" class="btn btn-tool">
                                    <i class="fas fa-plus-circle" style="font-size: 1.5em;"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="datatable-bebas" class="table table-bordered table-striped text-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">NIM</th>
                                        <th scope="col">Mahasiswa</th>
                                        <th scope="col">Pembimbing</th>
                                        <th scope="col">Judul TA</th>
                                        <th scope="col">Tahun Akademik</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mahasiswa_comp as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->mhs_nim }}</td>
                                            <td>{{ $item->mhs_nama }}</td>
                                            @if(isset($item->dosen_nama))
                                                <td>
                                                    <ol class="px-2">
                                                        @foreach ($item->dosen as $dosen)
                                                            <li class="m-0 p-0">{{ $dosen['dosen_nama'] }}</li>
                                                        @endforeach
                                                    </ol>
                                                </td>
                                            @else
                                                <td><span class="badge badge-danger">Belum Diplotting</span></td>
                                            @endif
                                            <td>{{ $item->ta_judul }}</td>
                                            <td>{{ $item->tahun_akademik }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-info dropdown-toggle"
                                                            data-toggle="dropdown">
                                                        <i class="fas fa-cog"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item"
                                                           href="{{ route('bimbingan.edit', $item->ta_id) }}">Edit</a>
                                                        <form method="POST"
                                                              action="{{ route('bimbingan.destroy', $item->ta_id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="dropdown-item confirm-button">Hapus
                                                            </button>
                                                        </form>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item"
                                                           href="{{ route('bimbingan.bimblog', $item->ta_id) }}">Lihat Bimbingan</a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('bimbingan.show', $item->ta_id) }}">Detail Persyaratan</a>
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

    @push('js')
    <!-- DataTables & Plugins -->
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

    <!-- Additional DataTables Initialization -->
    <script>
        $(document).ready(function() {
            // Check if DataTable is already initialized
            if (!$.fn.DataTable.isDataTable('#datatable-bebas')) {
                $('#datatable-bebas').DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#datatable-bebas_wrapper .col-md-6:eq(0)');
            }
        });
    </script>
@endpush
@endsection
