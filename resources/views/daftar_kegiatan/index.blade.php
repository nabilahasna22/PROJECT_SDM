@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Kegiatan</h3>
        </div>
        <div class="card-body">
            <!-- untuk Filter data -->
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_date" class="col-md-1 col-formlabel">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_kategori" class="form-control form-control-sm filter_kategori">
                                    <option value="">- Semua Kategori -</option>
                                    @foreach ($kategori as $l)
                                        <option value="{{ $l->kategori_id }}">{{ $l->kategori_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Kategori Kegiatan</small>
                            </div>
                            <div class="col-md-3">
                                <select name="filter_wilayah" class="form-control form-control-sm filter_wilayah">
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

            <!-- Flash message untuk sukses dan error -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Tabel Data Kegiatan -->
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
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk aksi (Detail/Edit/Hapus) -->
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false"
        data-width="75%"></div>
@endsection

@push('js')
    <script>
        // Fungsi untuk membuka modal
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var tableKegiatan;
        $(document).ready(function() {
            // Menampilkan DataTable
            tableKegiatan = $('#table-kegiatan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('kegiatan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.filter_kategori = $('.filter_kategori').val(); // Filter berdasarkan kategori
                        d.filter_wilayah = $('.filter_wilayah').val(); // Filter berdasarkan wilayah
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
                        return new Date(data).toLocaleDateString('id-ID'); // Format tanggal mulai
                    }
                }, {
                    data: "tanggal_selesai",
                    className: "",
                    orderable: true,
                    searchable: false,
                    render: function(data) {
                        return new Date(data).toLocaleDateString('id-ID'); // Format tanggal selesai
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
                }]
            });

            // Event untuk pencarian berdasarkan kata kunci (Enter Key)
            $('#table-kegiatan_filter input').unbind().bind().on('keyup', function(e) {
                if (e.keyCode == 13) { // Enter key
                    tableKegiatan.search(this.value).draw();
                }
            });

            // Event ketika kategori atau wilayah dipilih
            $('.filter_kategori, .filter_wilayah').change(function() {
                tableKegiatan.draw();
            });
        });
    </script>
@endpush
