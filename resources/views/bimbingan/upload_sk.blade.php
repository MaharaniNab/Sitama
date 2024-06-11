@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title m-0">Detail Persyaratan</h5>
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
                                    <p class="font-weight-bold">Nama Mahasiswa</p>
                                </div>
                                <div class="col">
                                    <p>: {{ $ta_mahasiswa->mhs_nama }}</p>
                                </div>
                            </div>
                            @foreach ($ta_mahasiswa->dosen as $pembimbing)
                                <div class="row">
                                    <div class="col col-md-4">
                                        <p class="font-weight-bold">Dosen Pembimbing {{ $loop->iteration }}</p>
                                    </div>
                                    <div class="col">
                                        <p>: {{ $pembimbing['dosen_nama'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col col-md-4">
                                    <p class="font-weight-bold">Tahun Akademik</p>
                                </div>
                                <div class="col">
                                    <p>: {{ $ta_mahasiswa->tahun_akademik }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-4">
                                    <p class="font-weight-bold">Judul TA</p>
                                </div>
                                <div class="col">
                                    <p>: {{ isset($ta_mahasiswa->judul_final) ? $ta_mahasiswa->judul_final : 'Data tidak tersedia' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">
                                Syarat Ujian TA
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Syarat</th>
                                                <th scope="col">File</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($syarat as $s)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $s->dokumen_syarat }}</td>
                                                    <td>
                                                        @if (!isset($s->dokumen_nama))
                                                            -
                                                        @else
                                                            <a href="{{ asset('storage/syarat_ta/' . $s->dokumen_nama) }}">{{ $s->dokumen_nama }}</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (!isset($s->dokumen_nama))
                                                        <span class="badge badge-danger">Belum Diupload</span>
                                                        @elseif ($s->verified == 0)
                                                            <form action="{{ route('bimbingan.verify', $s->syarat_sidang_id) }}" method="POST" class="d-inline verify-form">
                                                                @csrf
                                                                <input type="hidden" name="verified" value="1">
                                                                <button type="submit" class="btn btn-primary verifikasi">Verifikasi</button>
                                                            </form>
                                                            <span class="not-verified"></span>
                                                        @else
                                                            <span class="badge badge-success">Verified</span>
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
@endsection

@push('js')
    <!-- Tambahkan script JavaScript jika diperlukan -->
    <script>
        $('.verifikasi').click(function(event) {
            var form = $(this).closest("form");
            var notVerifiedSpan = $(this).closest('td').find('.not-verified');
            var button = $(this);
            event.preventDefault();
            swal({
                title: `Verifikasi Data`,
                icon: "warning",
                buttons: {
                    confirm: {
                        text: 'Ya'
                    },
                    cancel: 'Tidak'
                },
                dangerMode: true,
            }).then((willVerify) => {
                if (willVerify) {
                    form.find('input[name="verified"]').val(1);
                    form.submit();
                } else {
                    form.find('input[name="verified"]').val(2);
                    $.ajax({
                        type: 'POST',
                        url: form.attr('action'),
                        data: form.serialize(),
                        success: function(response) {
                            notVerifiedSpan.html('<span class="badge badge-danger">Not Verified</span>');
                            button.hide();
                        }
                    });
                }
            });
        });
    </script>
@endpush
