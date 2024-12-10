<form action="{{ url('/kegiatan/ajax') }}" method="POST" id="form-create">
    @csrf
    <div id="myModal" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Kategori Kegiatan -->
                <div class="form-group">
                    <label>Kategori Kegiatan</label>
                    <select name="kategori_id" id="kategori_id" class="form-control" required>
                        <option value="">- Pilih Kategori Kegiatan -</option>
                        @foreach ($kategori as $l)
                            <option value="{{ $l->kategori_id }}">{{ $l->kategori_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-kategori_id" class="text-danger"></small>
                </div>
                
                <!-- Periode -->
                <div class="form-group">
                    <label>Periode Kegiatan</label>
                    <select name="periode_id" id="periode_id" class="form-control" required>
                        <option value="">- Pilih Periode -</option>
                        @foreach ($periode as $l)
                            <option value="{{ $l->periode_id }}">{{ $l->tahun }}</option>
                        @endforeach
                    </select>
                    <small id="error-kategori_id" class="text-danger"></small>
                </div>
                <!-- Wilayah -->
                <div class="form-group">
                    <label>Wilayah</label>
                    <select name="id_wilayah" id="id_wilayah" class="form-control" required>
                        <option value="">- Pilih Wilayah -</option>
                        @foreach ($wilayah as $w)
                            <option value="{{ $w->id_wilayah }}">{{ $w->nama_wilayah }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_wilayah" class="text-danger"></small>
                </div>

                <!-- Nama Kegiatan -->
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input type="text" name="kegiatan_nama" id="kegiatan_nama" class="form-control" required>
                    <small id="error-kegiatan_nama" class="text-danger"></small>
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea>
                    <small id="error-deskripsi" class="text-danger"></small>
                </div>

                <!-- Tanggal Mulai -->
                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input 
                        type="date" 
                        name="tanggal_mulai" 
                        id="tanggal_mulai" 
                        class="form-control" 
                        required>
                    <small id="error-tanggal_mulai" class="text-danger"></small>
                </div>

                <!-- Tanggal Selesai -->
                <div class="form-group">
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                    <input 
                        type="date" 
                        name="tanggal_selesai" 
                        id="tanggal_selesai" 
                        class="form-control" 
                        required>
                    <small id="error-tanggal_selesai" class="text-danger"></small>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="on progres">On Progres</option>
                        <option value="terlaksana">Terlaksana</option>
                    </select>
                    <small id="error-status" class="text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        $("#form-create").validate({
            rules: {
                kategori_id: { required: true, number: true },
                id_wilayah: { required: true, number: true },
                periode_id: { required: true, number: true },
                kegiatan_nama: { required: true, minlength: 3, maxlength: 100 },
                deskripsi: { maxlength: 255 },
                tanggal_mulai: { required: true, date: true },
                tanggal_selesai: { required: true, date: true },
                status: { required: true }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            tableKegiatan.ajax.reload(); // Refresh the table
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
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
