@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Kelola Kegiatan</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/kegiatan/import') }}')" class="btn btn-info">Import Data</button>
                <a href="{{ url('/kegiatan/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
                <a href="{{ url('/kegiatan/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF</a>
                <button onclick="modalAction('{{ url('/kegiatan/create_ajax') }}')" class="btn btn-success">Tambah Data</button>
            </div>
        </div>
        <div class="card-body">
            <!-- untuk Filter data -->
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_date" class="col-md-1 col-formlabel">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_kategori" class="form-control formcontrol-sm filter_kategori">
                                    <option value="">- Semua Kategori -</option>
                                    @foreach ($kategori as $l)
                                        <option value="{{ $l->kategori_id }}">{{ $l->kategori_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Kategori Kegiatan</small>
                            </div>
                            <div class="col-md-3">
                                <select name="filter_wilayah" class="form-control formcontrol-sm filter_wilayah">
                                    <option value="">- Semua Wilayah -</option>
                                    @foreach ($wilayah as $w)
                                        <option value="{{ $w->id_wilayah }}">{{ $w->nama_wilayah }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Wilayah</small>
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
            <table class="table table-bordered table-sm table-striped table-hover" id="table-kegiatan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Kategori</th>
                        <th>Wilayah</th> <!-- Tambahkan kolom Wilayah -->
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
        var tableKegiatan;
        $(document).ready(function() {
            tableKegiatan = $('#table-kegiatan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('kegiatan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.filter_kategori = $('.filter_kategori').val();
                        d.filter_wilayah = $('.filter_wilayah').val(); // Filter untuk wilayah
                    }
                },
                columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "kegiatan_nama",
                    className: "",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "deskripsi",
                    className: "",
                    width: "15%",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "tanggal_mulai",
                    className: "",
                    orderable: true,
                    searchable: false,
                    render: function(data) {
                        return new Date(data).toLocaleDateString('id-ID');
                    }
                }, {
                    data: "tanggal_selesai",
                    className: "",
                    orderable: true,
                    searchable: false,
                    render: function(data) {
                        return new Date(data).toLocaleDateString('id-ID');
                    }
                }, {
                    data: "status",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "kategori.kategori_nama",
                    className: "",
                    orderable: true,
                    searchable: false
                }, {
                    data: "wilayah.nama_wilayah", // Menampilkan wilayah
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
            $('#table-kegiatan_filter input').unbind().bind().on('keyup', function(e) {
                if (e.keyCode == 13) { // enter key
                    tableKegiatan.search(this.value).draw();
                }
            });
            $('.filter_kategori, .filter_wilayah').change(function() {
                tableKegiatan.draw();
            });
        });
    </script>
@endpush
