@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title ?? 'Pending Approval List' }}</h3>
            {{-- Tambahkan tombol tambah jika diperlukan --}}
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
                        <th>ID</th>
                        <th>Kegiatan</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
    <script>
        $(document).ready(function() {
            var tablePendingApproval = $('#table_pending_approval').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    "url": "{{ url('approve_anggota/list') }}", // Ganti dengan URL endpoint yang sesuai
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
                        data: "kegiatan_nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "kategori.kategori_nama",
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
