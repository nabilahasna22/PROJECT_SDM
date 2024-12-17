@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            {{-- <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('level/create') }}">Tambah</a>
            </div> --}}
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <table class="table table-bordered table-striped table-hover table-sm" id="table_jabatan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Jabatan</th>
                        <th>Skor</th>
                        <th>IsPIC</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
    <script>
        $(document).ready(function() {
            var dataJabatan = $('#table_jabatan').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('jabatan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d){
                        d.jabatan_id = $('#jabatan_id').val();
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "nama_jabatan",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "skor",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "IsPIC",
                        className: "",
                        orderable: false,
                        searchable: false,
                    }
                ]
            });

            $('#id_jabatan').on('change', function(){
                dataJabatan.ajax.reload();
            });
        });
    </script>
@endpush