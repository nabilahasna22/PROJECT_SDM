@extends('layouts.template')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Upload Surat Tugas</div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('kegiatan.store_surat', $kegiatan->kegiatan_id) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label>Detail Kegiatan</label>
                            <input type="text" class="form-control" value="{{ $kegiatan->nama_kegiatan }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="surat_tugas">Pilih File Surat Tugas</label>
                            <input type="file" 
                                   class="form-control-file @error('surat_tugas') is-invalid @enderror" 
                                   id="surat_tugas" 
                                   name="surat_tugas"
                                   accept=".pdf,.doc,.docx">
                            
                            @error('surat_tugas')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Upload Surat Tugas
                            </button>
                            <a href="{{ url('/kegiatan') }}" class="btn btn-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection