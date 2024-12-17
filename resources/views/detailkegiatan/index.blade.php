@extends('layouts.template')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kegiatan Anggota</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/detailkegiatan/import') }}')" class="btn btn-info">Import Data</button>
            <a href="{{ url('/detailkegiatan/export_excel') }}" class="btn btn-primary">
                <i class="fa fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ url('/detailkegiatan/export_pdf') }}" class="btn btn-warning">
                <i class="fa fa-file-pdf"></i> Export PDF
            </a>
            <button onclick="modalAction('{{ url('/detailkegiatan/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-sm table-striped table-hover" id="table-kegiatan_anggota">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kegiatan</th>
                    <th>NIP</th>
                    <th>Jabatan</th>
                    <th>Bobot</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal untuk AJAX -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"></div>

@endsection

@push('js')
<script>
    // Fungsi untuk memuat modal AJAX
    function modalAction(url = '') {
        $.ajax({
            url: url,
            method: "GET",
            success: function(response) {
                $('#myModal').html(response).modal('show');
            },
            error: function(xhr) {
                alert('Terjadi kesalahan saat memuat data.');
            }
        });
    }

    $(document).ready(function() {
        var table = $('#table-kegiatan_anggota').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('detailkegiatan/list') }}",
                type: "POST",
                data: function(d) {
                    d.filter_kategori = $('.filter_kategori').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'kegiatan.kegiatan_nama', defaultContent: '-' },
                { data: 'user.nip', defaultContent: '-' },
                { data: 'jabatan.nama_jabatan', defaultContent: '-' },
                { data: 'bobot' },
                { data: 'aksi', orderable: false, searchable: false }
            ]
        });

        $('.filter_kategori').change(function() {
            table.draw();
        });
    });

    function saveData() {
        let form = $('#form-create');
        let url = "{{ url('detailkegiatan/store_ajax') }}";

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    $('#myModal').modal('hide');
                    $('#table-kegiatan_anggota').DataTable().ajax.reload();
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    }
</script>

<style>
    /* Pastikan modal tampil di atas semua elemen */
    .modal {
        z-index: 1050;
    }
    .modal-backdrop {
        z-index: 1040;
    }
</style>

@endpush
