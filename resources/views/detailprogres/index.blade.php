@extends('layouts.template')
@section('content')
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/kategori/import') }}')" class="btn btn-info">Import Data</button>
                <a href="{{ url('/kategori/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
                <a href="{{ url('/kategori/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF</a>
                <button onclick="modalAction('{{ url('/kategori/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Tabel akan diisi melalui DataTable -->
                </tbody>
            </table>
            <div id="noDataMessage" style="display: none; text-align: center; margin-top: 20px;">
                <p>No data available</p>
            </div>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataKategori;
        $(document).ready(function() {
            dataKategori = $('#table_kategori').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('kategori/list') }}",
                    dataType: "json",
                    type: "POST",
                    success: function(data) {
                        if (data.data.length === 0) {
                            $('#noDataMessage').show();  // Menampilkan pesan jika data kosong
                        } else {
                            $('#noDataMessage').hide();  // Menyembunyikan pesan jika data ada
                        }
                    },
                    error: function() {
                        $('#noDataMessage').show();  // Menampilkan pesan jika terjadi error dalam mengambil data
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
                        data: "kategori_kode",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "kategori_nama",
                        orderable: true,
                        searchable: true
                    }
                ],
                language: {
                    emptyTable: "No data available in table"
                }
            });

            $('#table-kategori_filter input').unbind().bind().on('keyup', function(e) {
                if (e.keyCode == 13) { // enter key
                    dataKategori.search(this.value).draw();
                }
            });

            $('.filter_kategori').change(function() {
                dataKategori.draw();
            });
        });
    </script>
@endpush
