@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0">Selamat datang {{ ucwords(auth()->user()->name) }}</h4>
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
                            <h5 class="m-0">SELAMAT DATANG DI DASHBOARD SITAMA</h5>
                        </div>
                        <div class="card-body">
                            <div class="moving-text-container">
                                <div class="moving-text">Sistem Informasi Tugas Akhir Mahasiswa</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        @keyframes moveText {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(calc(-100% - 200px));
            }
        }

        .moving-text-container {
            overflow: hidden;
            width: calc(100% + 200px);
        }

        .moving-text {
            animation: moveText 12s ease-in-out infinite;
            white-space: nowrap;
            display: inline-block;
            font-size: 1.2em;
            text-shadow: 0 0 10px rgba(0, 123, 255, 0.7), 0 0 20px rgba(0, 123, 255, 0.5), 0 0 30px rgba(0, 123, 255, 0.3);
            color: #007bff; /* Warna teks yang diperbarui */
            letter-spacing: 2px;
        }
    </style>
@endpush
