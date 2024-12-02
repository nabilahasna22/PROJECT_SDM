@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($user)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $user->level_id }}</td> <!-- Menggunakan level_id sebagai ID -->
                    </tr>
                    <tr>
                        <th>NIP</th>
                        <td>{{ $user->nip }}</td> <!-- Menampilkan NIP -->
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td>{{ $user->username }}</td> <!-- Menampilkan Username -->
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $user->nama }}</td> <!-- Menampilkan Nama -->
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td> <!-- Menampilkan Email -->
                    </tr>
                    <tr>
                        <th>No Telepon</th>
                        <td>{{ $user->no_telp }}</td> <!-- Menampilkan No Telepon -->
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $user->alamat }}</td> <!-- Menampilkan Alamat -->
                    </tr>
                    <tr>
                        <th>Level</th>
                        <td>{{ $user->level->level_nama ?? '-' }}</td> <!-- Menampilkan nama level yang terhubung -->
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td>********</td> <!-- Password disembunyikan -->
                    </tr>
                </table>
            @endempty
            <a href="{{ url('user') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
@endpush
