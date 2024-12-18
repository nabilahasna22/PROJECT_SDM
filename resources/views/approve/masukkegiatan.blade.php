@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Nama Kegiatan : {{$kegiatan->kegiatan_nama}}</h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm" id="table_pending_approval">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama Dosen</th>
                        <th>Jabatan</th>
                        <th>Bobot</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
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

        $(document).ready(function() {
            var tablePendingApproval = $('#table_pending_approval').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    "url": "{{ url('approve_anggota/list/'.$kegiatan->kegiatan_id) }}", // Ganti dengan URL endpoint yang sesuai
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d){
                        d.filter = $('#filter').val(); // Tambahkan filter jika diperlukan
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
                        data: "dosen_nip",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "dosen.nama",
                        className: "text-center",
                        orderable: true,
                        searchable: false
                    },{
                        data: "jabatan.nama_jabatan",
                        className: "text-center",
                        orderable: true,
                        searchable: false
                    },{
                        data: "bobot",
                        className: "text-center",
                        orderable: true,
                        searchable: false
                    },{
                        data: "status",
                        className: "text-center",
                        orderable: true,
                        searchable: false
                    },
                  {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                  }
                ]
            });

            $('#filter').on('change', function(){
                tablePendingApproval.ajax.reload();
            });
        });



    </script>
@endpush