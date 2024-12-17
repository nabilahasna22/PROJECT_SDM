
@empty($kegiatan)
<div id="myModal" class="modal-dialog modal-lg" role="document">
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
<form action="{{ url('/kegiatan/' . $kegiatan->kegiatan_id . '/update_ajax') }}" method="POST" id="form-edit" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div id="myModal" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-edit"></i> Edit Informasi Kegiatan</h5>
                </div>
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
                
                <!-- Wilayah -->
                <div class="form-group">
                    <label>Wilayah</label>
                    <select name="id_wilayah" id="id_wilayah" class="form-control" required>
                        <option value="">- Pilih Wilayah -</option>
                        @foreach ($wilayah as $w)
                            <option {{ $w->id_wilayah == $kegiatan->id_wilayah ? 'selected' : '' }} value="{{ $w->id_wilayah }}">{{ $w->nama_wilayah }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_wilayah" class="text-danger"></small>
                </div>

                <!-- Periode -->
                <div class="form-group">
                    <label>Periode</label>
                    <select name="periode_id" id="periode_id" class="form-control" required>
                        <option value="">- Pilih Periode -</option>
                        @foreach ($periode as $p)
                            <option {{ $p->periode_id == $kegiatan->periode_id ? 'selected' : '' }} value="{{ $p->periode_id }}">{{ $p->tahun }}</option>
                        @endforeach
                    </select>
                    <small id="error-periode_id" class="text-danger"></small>
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

                <!-- Pilih PIC -->
                <div class="form-group">
                    <label>Pilih PIC</label>
                    <select name="nip" id="nip" class="form-control" required>
                        <option value="">- Pilih PIC -</option>
                        @foreach ($user as $u)
                            <option {{ $u->nip == old('nip', isset($kegiatanAnggota) ? $kegiatanAnggota->nip : '') ? 'selected' : '' }} 
                                value="{{ $u->nip }}">
                                {{ $u->nip }} - {{ $u->nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-nip" class="text-danger"></small>
                </div>
                


                <!-- Surat Tugas -->
                <div class="form-group">
                    <label>Surat Tugas</label>
                    @if ($kegiatan->surat_tugas)
                        <p>
                            <a href="{{ url('kegiatan/download/' . $kegiatan->surat_tugas) }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i> Lihat Dokumen
                            </a>
                        </p>
                    @endif
                    <input type="file" name="surat_tugas" class="form-control">
                    <small class="form-text text-muted">Unggah dokumen (PDF/DOC/DOCX).</small>
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

                <!-- Status -->
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="on progres" {{ old('status', $kegiatan->status) == 'on progres' ? 'selected' : '' }}>On Progres</option>
                        <option value="terlaksana" {{ old('status', $kegiatan->status) == 'terlaksana' ? 'selected' : '' }}>Terlaksana</option>
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
    $('#form-edit').on('submit', function(e) {
    e.preventDefault();

    // Ambil data form
    let formData = new FormData(this);
    let formAction = $(this).attr('action');

    // Kirim AJAX request
    $.ajax({
        url: formAction,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
            // Tampilkan loading SweetAlert
            Swal.fire({
                title: 'Memproses...',
                text: 'Harap tunggu',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        },
        success: function(response) {
            // Jika berhasil
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: response.message,
            }).then(() => {
                // Reload halaman atau lakukan aksi lain
                location.reload();
            });
        },
        error: function(xhr) {
            // Jika gagal validasi atau error lainnya
            let errors = xhr.responseJSON.errors || {};
            let errorMessage = xhr.responseJSON.message || 'Terjadi kesalahan';

            // Tampilkan pesan error umum
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: errorMessage,
            });

            // Tampilkan error di setiap field (jika ada)
            for (let field in errors) {
                $(`#error-${field}`).text(errors[field][0]);
            }
        }
    });
});

</script>
@endempty
