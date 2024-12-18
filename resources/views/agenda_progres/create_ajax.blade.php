<!-- Form Tambah Progres -->
<form action="{{ url('/agenda_progres/create_ajax') }}" method="POST" id="form-create" enctype="multipart/form-data">
    @csrf
    <div id="myModal" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Progres</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nama Kegiatan -->
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input type="text" name="kegiatan_nama" id="kegiatan_nama" class="form-control" required>
                    <small id="error-kegiatan_nama" class="text-danger"></small>
                </div>

                <!-- NIP -->
                <div class="form-group">
                    <label>NIP</label>
                    <input type="text" name="nip" id="nip" class="form-control" required>
                    <small id="error-nip" class="text-danger"></small>
                </div>

                <!-- Nama Panitia -->
                <div class="form-group">
                    <label>Nama Panitia</label>
                    <input type="text" name="nama_panitia" id="nama_panitia" class="form-control" required>
                    <small id="error-nama_panitia" class="text-danger"></small>
                </div>

                <!-- File Dokumen -->
                <div class="form-group">
                    <label>File Dokumen</label>
                    <input type="file" name="file_dokumen" id="file_dokumen" class="form-control" required>
                    <small id="error-file_dokumen" class="text-danger"></small>
                </div>

                <!-- Keterangan File -->
                <div class="form-group">
                    <label>Keterangan File</label>
                    <textarea name="file_deskripsi" id="file_deskripsi" class="form-control" rows="3"></textarea>
                    <small id="error-file_deskripsi" class="text-danger"></small>
                </div>

                <!-- Status Progress -->
                <div class="form-group">
                    <label>Status Progress</label>
                    <select name="progress" id="progress" class="form-control" required>
                        <option value="on_progress">On Progress</option>
                        <option value="completed">Completed</option>
                        <option value="not_started">Not Started</option>
                    </select>
                    <small id="error-progress" class="text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<!-- DataTables -->
<table class="table table-bordered table-sm table-striped table-hover" id="table-agenda_progres">
    <thead>
        <tr>
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

<script>
    $(document).ready(function () {
        // Initialize DataTable
        const tableAgendaProgres = $('#table-agenda_progres').DataTable({
    destroy: true, // Mengizinkan reinitialisasi
    processing: true,
    serverSide: true,
    ajax: "{{ url('/agenda_progres/list') }}",
    columns: [
        { data: 'kegiatan.kegiatan_nama', orderable: true, searchable: true },
        { data: 'nip', orderable: true, searchable: true },
        { data: 'user.nama', orderable: true, searchable: true },
        {
            data: 'file_dokumen',
            orderable: false,
            searchable: false,
            render: function (data) {
                return data
                    ? '<a href="{{ url("agenda_progres/download") }}/' + data + '" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Download</a>'
                    : 'Tidak ada dokumen';
            }
        },
        { data: 'file_deskripsi', orderable: true, searchable: true },
        {
            data: 'progress',
            orderable: true,
            searchable: true,
            render: function (data) {
                switch (data) {
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
        { data: 'action', orderable: false, searchable: false }
    ]
});

        // Handle Form Submit
        $("#form-create").validate({
            rules: {
                kegiatan_nama: { required: true },
                nip: { required: true },
                nama_panitia: { required: true },
                file_dokumen: { required: true },
                progress: { required: true }
            },
            submitHandler: function (form) {
                let formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            tableAgendaProgres.ajax.reload(); // Refresh DataTable
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            }
        });

        // Reset form when modal is closed
        $('#myModal').on('hidden.bs.modal', function () {
            $('#form-create')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        });
    });
</script>
