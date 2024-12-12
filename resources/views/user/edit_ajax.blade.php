@empty($user)
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
                Data yang anda cari tidak ditemukan
            </div>
        </div>
    </div>
</div>
@else
<form action="{{ url('/user/' . $user->nip . '/update_ajax') }}" method="POST" id="form-edit" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- NIP -->
                <div class="form-group">
                    <label>NIP</label>
                    <input value="{{ $user->nip }}" type="text" name="nip" class="form-control" required>
                </div>

                <!-- Level -->
                <div class="form-group">
                    <label>Level</label>
                    <select name="level_id" class="form-control" required>
                        <option value="">- Pilih Level -</option>
                        @foreach($level as $l)
                            <option {{ ($l->level_id == $user->level_id) ? 'selected' : '' }} value="{{ $l->level_id }}">
                                {{ $l->level_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label>Username</label>
                    <input value="{{ $user->username }}" type="text" name="username" class="form-control" required>
                </div>

                <!-- Nama -->
                <div class="form-group">
                    <label>Nama</label>
                    <input value="{{ $user->nama }}" type="text" name="nama" class="form-control" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label>Email</label>
                    <input value="{{ $user->email }}" type="email" name="email" class="form-control" required>
                </div>

                <!-- No Telp -->
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input value="{{ $user->no_telp }}" type="text" name="no_telp" class="form-control">
                </div>

                <!-- Alamat -->
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control">{{ $user->alamat }}</textarea>
                </div>

                <!-- Foto -->
                <div class="form-group">
                    <label>Foto Profil</label>
                    <input type="file" name="foto" class="form-control">
                    <small class="form-text text-muted">Abaikan jika tidak ingin ubah foto profil</small>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control">
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
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
    $("#form-edit").validate({
        rules: {
            nip: {
                required: true,
                minlength: 8,
                maxlength: 20
            },
            level_id: {
                required: true,
                number: true
            },
            username: {
                required: true,
                minlength: 3,
                maxlength: 20
            },
            nama: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            email: {
                required: true,
                email: true
            },
            no_telp: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            alamat: {
                required: true,
                minlength: 10,
                maxlength: 255
            },
            foto: {
                extension: "jpg|jpeg|png|ico|bmp"
            },
            password: {
                minlength: 5,
                maxlength: 20
            }
        },
        submitHandler: function(form) {
            console.log("Mengirim permintaan Ajax...");
            let formData = new FormData(form);
            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log("Respons diterima:", response);
                    if (response.status) {
                        $('#modal-master').modal('hide'); // Pastikan modal tertutup
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataUser.ajax.reload(); // Reload tabel
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error terjadi:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan, coba lagi.'
                    });
                }
            });
        }
    });
});
</script>
@endempty