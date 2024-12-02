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
                <a href="{{ url('user') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('/user/' . $user->nip) }}" class="form-horizontal">
                    @csrf
                    @method('PUT')<!-- tambahkan baris ini untuk proses edit yang butuh method PUT -->
                    
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">NIP</label>
                        <div class="col-11">
                            <input type="text" class="form-control" id="nip" name="nip"
                                value="{{ old('nip', $user->nip) }}" required>
                            @error('nip')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Nama</label>
                        <div class="col-11">
                            <input type="text" class="form-control" id="nama" name="nama"
                                value="{{ old('nama', $user->nama) }}" required>
                            @error('nama')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Username</label>
                        <div class="col-11">
                            <input type="text" class="form-control" id="username" name="username"
                                value="{{ old('username', $user->username) }}" required>
                            @error('usernane')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">No Telp</label>
                        <div class="col-11">
                            <input type="text" class="form-control" id="no_telp" name="no_telp"
                                value="{{ old('no_telp', $user->no_telp) }}" maxlength="20">
                            @error('no_telp')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Alamat</label>
                        <div class="col-11">
                            <input type="text" class="form-control" id="alamat" name="alamat"
                                value="{{ old('alamat', $user->alamat) }}" maxlength="255">
                            @error('alamat')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Email</label>
                        <div class="col-11">
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Password</label>
                        <div class="col-11">
                            <input type="password" class="form-control" id="password" name="password">
                            @error('password')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @else
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti password user.</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Level</label>
                        <div class="col-11">
                            <select class="form-control" id="level_id" name="level_id" required>
                                <option value="">- Pilih Level -</option>
                                @foreach($Level as $l)
                                    <option value="{{ $l->level_id }}" 
                                        {{ old('level_id', $user->level_id) == $l->level_id ? 'selected' : '' }}>
                                        {{ $l->level_nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('level_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label"></label>
                        <div class="col-11">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <a class="btn btn-sm btn-default ml-1" href="{{ url('user') }}">Kembali</a>
                        </div>
                    </div>
                </form>
            @endempty
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush
