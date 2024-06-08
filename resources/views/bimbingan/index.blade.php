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
                                <a href="{{ route('bimbingan.create') }}" class="btn btn-tool"><i
                                        class="fas fa-plus-circle"></i></a>
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
                                        <th scope="col">Tahun Akademik</th>
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
                                            <?php
                                            if(isset($item->dosen_nama)) {
                                            ?>
                                            <td>
                                                <ol class="px-2">
                                                    @foreach ($item->dosen as $dosen)
                                                        <li class="m-0 p-0">{{ $dosen['dosen_nama'] }}</li>
                                                    @endforeach
                                                </ol>
                                            </td>
                                            <?php
                                            } else {
                                            ?>
                                            <td>Belum di plot</td>
                                            <?php
                                            }
                                            ?>
                                            {{-- <td>
                                        @php
                                            $pembimbing = \App\Models\Bimbingan::where('ta_id', $item->ta_id)->orderBy('urutan')->get();
                                            // dd($pembimbing);
                                            // Mengelompokkan pembimbing berdasarkan urutan
                                            $groupedPembimbing = $pembimbing->groupBy('urutan');
                                            // dd($groupedPembimbing);

                                            // Menampilkan nama dosen
                                            foreach ($groupedPembimbing as $urutan => $pembimbingGroup) {
                                                echo "$urutan.  ";
                                                foreach ($pembimbingGroup as $pem) {
                                                    $dosen = \App\Models\Dosen::where('dosen_nip', $pem->dosen_nip)->first();
                                                    echo $dosen->dosen_nama . "<br>";
                                                }
                                            }
                                        @endphp
                                    </td> --}}
                                            <td>{{ $item->ta_judul }}</td>
                                            <td>{{ $item->tahun_akademik }}</td>
                                            <td id="status-{{ $item->ta_id }}">{!! $item->verified === 1
                                                ? '<span class="badge badge-success">Verified</span>'
                                                : ($item->verified === 0
                                                    ? '<span class="badge badge-danger">Not Verified</span>'
                                                    : '<span class="badge badge-warning">Pending</span>') !!}</td>
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
                                                                class="dropdown-item confirm-button">Hapus</button>
                                                        </form>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('bimbingan.verify') }}" method="post">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item btn-verify"
                                                                name="data-id" value="{{ $item->ta_id }}">Verifikasi
                                                                Data</button>
                                                        </form>
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

    <!-- Tambahkan skrip JavaScript -->
    @push('js')
        <!-- DataTables  & Plugins -->
        <script>
            $('.toast').toast('show')
        </script>
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

        <script></script>
    @endpush
@endsection
