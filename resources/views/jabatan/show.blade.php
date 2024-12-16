@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @if(empty($jabatan) || !$jabatan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $jabatan->id_jabatan }}</td>
                    </tr>
                    <tr>
                        <th>Nama Jabatan</th>
                        <td>{{ $jabatan->nama_jabatan }}</td>
                    </tr>
                    <tr>
                        <th>Skor</th>
                        <td>{{ $jabatan->skor }}</td>
                    </tr>
                    <tr>
                        <th>IsPIC</th>
                        <td>{{ $jabatan->isPIC ? '1' : '0' }}</td>
                    </tr>
                </table>
            @endif
            <a href="{{ url('jabatan') }}" class="btn btn-sm btn-default mt-2">Kembali </a>
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
@endpush
