@extends('layouts.template')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kegiatan yang Dikuti oleh Dosen</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $dosenikutkegiatan }}</h3>
                    <p>Dosen yang Mengikuti Kegiatan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $progreskegiatanterlaksana }}</h3>
                    <p>Progres Kegiatan Terlaksana</p>
                </div>
                <div class="icon">
                    <i class="fas fa-sticky-note"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $progreskegiatanberjalan }}</h3>
                    <p>Progres Kegiatan Sedang Berjalan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-sticky-note"></i>
                </div>
            </div>
        </div>
    </div>

@endsection