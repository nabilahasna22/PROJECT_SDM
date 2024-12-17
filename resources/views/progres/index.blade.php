@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Progres</h3>
            <div class="card-tools">
                <!-- Filter status progress -->
                <select id="filter_progress" class="form-control form-control-sm">
                    <option value="">Semua Status</option>
                    <option value="on_progress">Sedang Berjalan</option>
                    <option value="completed">Selesai</option>
                    <option value="not_started">Belum Dimulai</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <table class="table table-bordered table-sm table-striped table-hover" id="table-agenda_progres">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kegiatan</th>
                        <th>NIP</th>
                        <th>File Dokumen</th>
                        <th>Keterangan File</th>
                    
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal untuk aksi -->
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
@endpush

@push('js')
    <!-- CSRF Token di Meta -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    <script>
        // Function untuk modal
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        // Function untuk menghapus data
        function deleteData(url) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#table-agenda_progres').DataTable().ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Terjadi kesalahan: ' + xhr.responseJSON.message);
                    }
                });
            }
        }

        $(document).ready(function() {
    var dataProgres = $('#table-agenda_progres').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('progres.list') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: function(d) {
                d.filter_progress = $('#filter_progress').val(); // Mengirim filter status progress ke backend
            }
        },
        columns: [
            { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
            { data: "kegiatan_nama", className: "", orderable: true, searchable: true },
            { data: "nip", className: "", orderable: true, searchable: true },
            {
                data: "file_dokumen",
                orderable: false,
                searchable: false,
                render: function(data) {
                    return data
                        ? '<a href="{{ route("progres.download", ":filename") }}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Download</a>'.replace(':filename', data)
                        : 'Tidak ada dokumen';
                }
            },
            { data: "file_deskripsi", className: "", orderable: true, searchable: true },
        ],
        error: function(xhr) {
            console.log('Error:', xhr.responseText);
        }
    });

    $('#filter_progress').on('change', function() {
        dataProgres.ajax.reload(); // Mengupdate data ketika filter berubah
    });
});

</script>
@endpush
