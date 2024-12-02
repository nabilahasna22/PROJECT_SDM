@empty($kegiatan)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data yang Anda cari tidak ditemukan
            </div>
            <a href="{{ url('/kegiatan') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/kegiatan/' . $kegiatan->kegiatan_id . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Kegiatan</h5>
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
                            <option {{ $l->kategori_id == $kegiatan->kategori_id ? 'selected' : '' }} value="{{ $l->kategori_id }}">{{ $l->kategori_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-kategori_id" class="text-danger"></small>
                </div>

                <!-- Nama Kegiatan -->
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input value="{{ old('kegiatan_nama', $kegiatan->kegiatan_nama) }}" type="text" name="kegiatan_nama" id="kegiatan_nama" class="form-control" required>
                    <small id="error-kegiatan_nama" class="text-danger"></small>
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
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
                        value="{{ old('tanggal_mulai', $kegiatan->tanggal_mulai) }}" 
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
                        value="{{ old('tanggal_selesai', $kegiatan->tanggal_selesai) }}" 
                        required>
                    <small id="error-tanggal_selesai" class="text-danger"></small>
                </div>

                <!-- Jenis Kegiatan -->
                <div class="form-group">
                    <label>Jenis Kegiatan</label>
                    <input 
                        value="{{ old('jenis_kegiatan', $kegiatan->jenis_kegiatan) }}" 
                        type="text" 
                        name="jenis_kegiatan" 
                        id="jenis_kegiatan" 
                        class="form-control" 
                        required>
                    <small id="error-jenis_kegiatan" class="text-danger"></small>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="onprogres" {{ old('status', $kegiatan->status) == 'onprogres' ? 'selected' : '' }}>On Progres</option>
                        <option value="completed" {{ old('status', $kegiatan->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ old('status', $kegiatan->status) == 'pending' ? 'selected' : '' }}>Pending</option>
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
@endempty

<script>
    $(document).ready(function() {
        $("#form-edit").validate({
            rules: {
                kategori_id: { required: true, number: true },
                kegiatan_nama: { required: true, minlength: 3, maxlength: 100 },
                deskripsi: { maxlength: 255 },
                tanggal_mulai: { required: true, date: true },
                tanggal_selesai: { required: true, date: true },
                jenis_kegiatan: { required: true, maxlength: 50 },
                status: { required: true }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            tableKegiatan.ajax.reload();
                            $('#myModal').modal('hide');
                        } else {
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
    });
</script>
