@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Statistik Dosen</h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm" id="table_statistik_dosen">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dosen</th>
                        <th>Total Kegiatan</th>
                        <th>Terprogram</th>
                        <th>Non Program</th>
                        <th>Non JTI</th>
                        <th>Total Bobot</th>
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
            $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});


            var dataStatistikDosen = $('#table_statistik_dosen').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('statistik_dosen.list') }}",
                    type: "POST",
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        alert('Terjadi kesalahan: ' + error);
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "nama_dosen", className: "text-center" },
                    { data: "total_kegiatan", className: "text-center" },
                    { data: "terprogram", className: "text-center" },
                    { data: "non_program", className: "text-center" },
                    { data: "non_jti", className: "text-center" },
                    { data: "total_bobot", className: "text-center" },
                    { 
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <a href="{{ url('dosen/edit') }}/${row.id}" class="text-primary">[Edit]</a>
                                <form action="{{ url('dosen/delete') }}/${row.id}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 m-0" style="text-decoration: none;" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">[Delete]</button>
                                </form>
                            `;
                        }
                    }
                ],
                order: [[1, 'asc']]
            });
        });
    </script>
@endpush
