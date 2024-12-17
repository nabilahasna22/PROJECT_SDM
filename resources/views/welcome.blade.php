@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Dashboard</h3>
    </div>
    <div class="card-body">
        Selamat datang, {{ Auth::user()->name }}! Ini adalah halaman utama aplikasi.
    </div>
</div>

{{-- Include dashboard sesuai level pengguna --}}
@if(Auth::user()->level_id == '3')
    @include('dashboard.admin')
@elseif(Auth::user()->level_id == '2')
    @include('dashboard.dosen')
@elseif(Auth::user()->level_id == '1')
    @include('dashboard.pimpinan')
@endif
@endsection
