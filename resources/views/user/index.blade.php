@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar User</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/user/import') }}')" class="btn btn-info">Import Data User</button>
                <a href="{{ url('/user/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Data User</a>
                <a href="{{ url('/user/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Data User</a>
                <button onclick="modalAction('{{ url('/user/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="filter_level_id" name="level_id" required>
                                <option value="">- Semua -</option>
                                @foreach ($level as $item)
                                    <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Level Pengguna</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive"> <!-- Membuat tabel responsif -->
                <table class="table table-bordered table-sm table-striped table-hover" id="table-user">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No Telepon</th>
                            <th>Alamat</th>
                            <th>Level</th>
                            <th>Foto Profil</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false"
        data-width="75%"></div>
@endsection

@push('css')
<style>
    .foto-profil {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
    }
    .foto-placeholder {
        width: 50px;
        height: 50px;
        background-color: #ccc;
        display: inline-block;
        border-radius: 50%;
    }
</style>
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var dataUser;
        $(document).ready(function() {
            dataUser = $('#table-user').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('user/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.filter_level = $('#filter_level_id').val();
                    }
                },
                columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "nip",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "username",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "nama",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "email",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "no_telp",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "alamat",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "level.level_nama",
                    orderable: true,
                    searchable: false
                }, {
                    data: "foto",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (data) {
                            return `<img src="${data}" alt="Foto Profil" class="foto-profil">`;
                        } else {
                            return `<div class="foto-placeholder"></div>`;
                        }
                    }
                }, {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }]
            });

            $('#filter_level_id').on('change', function() {
                dataUser.ajax.reload();
            });
        });
    </script>
@endpush
