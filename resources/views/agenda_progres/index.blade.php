@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Agenda Progress</h3>
            <div class="card-tools">
                {{-- <button onclick="modalAction('{{ url('/agenda_progres/create_ajax') }}')" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah Data
                </button> --}}
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-form-label col-1">Filter:</label>
                        {{-- <div class="col-3">
                            <select class="form-control" id="filter_kegiatan" name="kegiatan_id">
                                <option value="">- Semua Kegiatan -</option>
                                @foreach ($kegiatan as $item)
                                    <option value="{{ $item->kegiatan_id }}">{{ $item->nama_kegiatan }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Filter Berdasarkan Kegiatan</small>
                        </div> --}}
                        <div class="col-3">
                            <select class="form-control" id="filter_progress" name="progress">
                                <option value="">- Semua Status -</option>
                                <option value="on_progress">On Progress</option>
                                <option value="completed">Completed</option>
                                <option value="not_started">Not Started</option>
                            </select>
                            <small class="form-text text-muted">Filter Berdasarkan Progress</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <table class="table table-bordered table-sm table-striped table-hover" id="table-agenda_progres">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kegiatan</th>
                        <th>NIP</th>
                        <th>Nama Panitia</th>
                        <th>File Dokumen</th>
                        <th>Keterangan File</th>
                        <th>Status Progress</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal untuk AJAX -->
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
@endsection

@push('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@push('js')
    <!-- DataTables JS -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>

    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

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
            var dataAgendaprogres = $('#table-agenda_progres').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('agenda_progres/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d){
                        // d.filter_kegiatan = $('#filter_kegiatan').val();
                        d.filter_progress = $('#filter_progress').val();
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
                        data: "kegiatan.kegiatan_nama",
                        className: "",
                        orderable: true,
                        searchable: true,
                    }, 
                    {
                        data: "nip",
                        className: "",
                        orderable: true,
                        searchable: true,
                    }, 
                    {
                        data: "user.nama",
                        className: "",
                        orderable: true,
                        searchable: true,
                    }, 
                    {
                        data: "file_dokumen",
                        className: "",
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return data ? 
                                '<a href="{{ url("agenda_progres/download") }}/' + data + '" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Download</a>' : 
                                'Tidak ada dokumen';
                        }
                    },
                    {
                        data: "file_deskripsi",
                        className: "",
                        orderable: true,
                        searchable: true,
                    },  
                    {
                        data: "progress",
                        className: "",
                        orderable: true,
                        searchable: true,
                        render: function(data) {
                            switch(data) {
                                case 'on_progress':
                                    return '<span class="badge badge-warning">On Progress</span>';
                                case 'completed':
                                    return '<span class="badge badge-success">Completed</span>';
                                case 'not_started':
                                    return '<span class="badge badge-danger">Not Started</span>';
                                default:
                                    return '<span class="badge badge-secondary">Unknown</span>';
                            }
                        }
                    }, 
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Filter event
            $('#filter_progress').on('change', function(){
                dataAgendaprogres.ajax.reload();
            });
        });
    </script>
@endpush