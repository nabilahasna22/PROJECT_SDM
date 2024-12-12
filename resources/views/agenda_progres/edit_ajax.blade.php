@empty($agenda_progres)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data yang Anda cari tidak ditemukan.
            </div>
        </div>
    </div>
</div>
@else
<form id="form-edit" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Agenda Progress</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Kegiatan -->
                <div class="form-group">
                    <label>Kegiatan</label>
                    <select name="kegiatan_id" class="form-control" required>
                        <option value="">- Pilih Kegiatan -</option>
                        @foreach($kegiatan as $k)
                            <option value="{{ $k->kegiatan_id }}" {{ $k->kegiatan_id == $agenda_progres->kegiatan_id ? 'selected' : '' }}>
                                {{ $k->kegiatan_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- NIP -->
                <div class="form-group">
                    <label>NIP</label>
                    <input value="{{ $agenda_progres->nip }}" type="text" name="nip" class="form-control" readonly>
                </div>

                <!-- File Dokumen -->
                <div class="form-group">
                    <label>File Dokumen</label>
                    @if ($agenda_progres->file_dokumen)
                        <p>
                            <a href="{{ url('agenda_progres/download/' . $agenda_progres->file_dokumen) }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i> Lihat Dokumen
                            </a>
                        </p>
                    @endif
                    <input type="file" name="file_dokumen" class="form-control">
                    <small class="form-text text-muted">Unggah dokumen (PDF/DOC/DOCX).</small>
                </div>

                <!-- Deskripsi File -->
                <div class="form-group">
                    <label>Keterangan File</label>
                    <input value="{{ $agenda_progres->file_deskripsi }}" type="text" name="file_deskripsi" class="form-control" required>
                </div>

                <!-- Status Progress -->
                <div class="form-group">
                    <label>Status Progress</label>
                    <select name="progress" class="form-control" required>
                        <option value="not_started" {{ $agenda_progres->progress == 'not_started' ? 'selected' : '' }}>Not Started</option>
                        <option value="on_progress" {{ $agenda_progres->progress == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                        <option value="completed" {{ $agenda_progres->progress == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
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
    $(document).ready(function() {
        $('#form-edit').on('submit', function(e) {
            e.preventDefault(); // Mencegah form submit default
            let formData = new FormData(this);
            let url = "{{ url('/agenda_progres/update/' . $agenda_progres->id_progres) }}";

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        }).then(() => {
                            // Tutup modal
                            $('#myModal').modal('hide');
                            // Muat ulang tabel data
                            $('#table-agenda_progres').DataTable().ajax.reload(null, false);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan, coba lagi.'
                    });
                }
            });
        });
    });
</script>
@endempty
