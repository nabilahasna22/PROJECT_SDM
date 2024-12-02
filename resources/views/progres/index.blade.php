@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Progres</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/progres/import') }}')" class="btn btn-info">Import Data</button>
                <a href="{{ url('/progres/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
                <a href="{{ url('/progres/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF</a>
                <button onclick="modalAction('{{ url('/progres/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            <!-- untuk Filter data -->
            
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-sm table-striped table-hover" id="table-progres">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kegiatan</th>
                        <th>NIP</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
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
        var tableProgres;
        $(document).ready(function() {
            tableProgres = $('#table-progres').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('progres/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.filter_kategori = $('.filter_kategori').val();
                    }
                },
                columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "kegiatan.kegiatan_nama",
                    className: "",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "nip",
                    className: "",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "tanggal",
                    className: "",
                    orderable: true,
                    searchable: false,
                    render: function(data) {
                        return new Date(data).toLocaleDateString('id-ID');
                    }
                }, {
                    data: "deskripsi",
                    className: "",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }]
            });
            $('#table-progres_filter input').unbind().bind().on('keyup', function(e) {
                if (e.keyCode == 13) { // enter key
                    tableProgres.search(this.value).draw();
                }
            });
            $('.filter_kategori').change(function() {
                tableProgres.draw();
            });
        });
    </script>
@endpush
