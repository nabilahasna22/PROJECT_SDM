@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar User</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/user/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter data -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label" for="level_id">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="level_id" name="level_id" required>
                                <option value="">- Semua -</option>
                                @foreach($level as $item)
                                    <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Level Pengguna</small>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false"
        data-width="75%"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var tableUser;
        $(document).ready(function() {
            tableUser = $('#table-user').DataTable({
            ajax: {
                "url": "{{ url('user/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d){
                    d.level_id=$('#level_id').val();
                }
            },
                columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "nip",
                    className: "",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "username",
                    className: "",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "nama",
                    className: "",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "email",
                    className: "",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "no_telp",
                    className: "",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "alamat",
                    className: "",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "level.level_nama",
                    className: "",
                    orderable: true,
                    searchable: false
                }, {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }]
            });

            $('#level_id').on('change', function(){
            tableUser.ajax.reload();
        });
        });
    </script>
@endpush
