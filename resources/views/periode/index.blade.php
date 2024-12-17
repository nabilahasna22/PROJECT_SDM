@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Tabel Periode Kegiatan</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/periode/create_ajax') }}')" class="btn btn-success">Tambah Periode</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_periode">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
@endsection
@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }
    var tablePeriode;
    $(document).ready(function() {
        tablePeriode = $('#table_periode').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{{ url('periode/list') }}",
                "dataType": "json",
                "type": "POST",
            },
            columns: [
                {
                    data: "periode_id",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "tahun",
                    className: "",
                    orderable: true,
                    searchable: true
                }]
        });
    });
</script>
@endpush