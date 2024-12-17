<form action="{{ url('/request/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Anggota</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Dosen</label>
                    <select name="dosen_nip" id="dosen_nip" class="form-control" required>
                        <option value="">- Pilih Dosen -</option>
                        @foreach ($dosen as $item)
                            <option value="{{$item->nip}}">{{$item->nama}}</option>
                        @endforeach
                    </select>
                    <small id="error-nama_dosen" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Posisi</label>
                    <select name="posisi_id" id="posisi_id" class="form-control" required>
                        <option value="">- Pilih Jabatan -</option>
                        @foreach ($jabatan as $item)
                            <option value="{{$item->id_jabatan}}" data-skor="{{$item->skor}}">{{$item->nama_jabatan}}</option>
                        @endforeach
                    </select>
                    <small id="error-nama_dosen" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group" id="form-skor-jabatan" style="display:none;">
                    <label>Skor Jabatan</label>
                    <input type="number" name="skor_jabatan" id="skor_jabatan" class="form-control" readonly>
                    <small id="error-skor_jabatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Skor Wilayah</label>
                    <input type="number" name="skor_wilayah" id="skor_wilayah" class="form-control" value="{{$skor}}" readonly>
                    <small id="error-skor_wilayah" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Hasil Bobot</label>
                    <input type="number" name="bobot" id="bobot" class="form-control" readonly>
                    <small id="error-bobot" class="error-text form-text text-danger"></small>
                </div>
                <input type="text" value="pending" name="status" hidden>
                <input type="text" value="{{$kegiatan->kegiatan_id}}" name="kegiatan_id" hidden>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
    $("#form-tambah").validate({
        rules: {
            dosen_nip: {required: true},
            kegiatan_id: {required: true},
            posisi_id: {required: true},
            bobot: {required: true},
            status: {required: true},
        },

        submitHandler: function(form) {
            $.ajax({
                url: form.action, // URL untuk mengirim data
                type: form.method, // POST method
                data: $(form).serialize(), // data form yang disubmit
                success: function(response) {
                    console.log(response); // Log response untuk memeriksa apakah data sudah diterima

                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        }).then(() => {
                            window.location.reload(); // Reload halaman setelah SweetAlert ditekan
                        });
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'berhasil',
                            text: response.message
                        }).then(() => {
                            window.location.reload(); // Reload halaman setelah SweetAlert ditekan
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error); // Menampilkan error jika permintaan gagal
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal mengirim data. Coba lagi nanti.'
                    });
                }
            });
            return false;
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});


    $(document).ready(function() {
        // Ketika jabatan dipilih
        $('#posisi_id').change(function() {
            var selectedJabatan = $(this).find(':selected'); // Mendapatkan jabatan yang dipilih
            var skorJabatan = selectedJabatan.data('skor'); // Mengambil skor dari atribut data-skor
            $('#skor_jabatan').val(skorJabatan); // Mengupdate input skor jabatan

            // Menampilkan form-group skor jabatan jika jabatan dipilih
            if (skorJabatan) {
                $('#form-skor-jabatan').show(); // Menampilkan form-group skor jabatan
            } else {
                $('#form-skor-jabatan').hide(); // Menyembunyikan form-group skor jabatan jika tidak ada jabatan yang dipilih
            }

            // Menghitung bobot berdasarkan skor wilayah dan skor jabatan
            calculateBobot();
        });

        // Ketika skor wilayah diubah
        $('#skor_wilayah').on('input', function() {
            // Menghitung bobot berdasarkan skor wilayah dan skor jabatan
            calculateBobot();
        });

        // Menghitung bobot
        function calculateBobot() {
            var skorWilayah = parseInt($('#skor_wilayah').val()) || 0;
            var skorJabatan = parseInt($('#skor_jabatan').val()) || 0;
            var bobot = skorWilayah + skorJabatan;
            $('#bobot').val(bobot); // Menampilkan bobot di input
        }

        // Set skor jabatan awal (jika ada nilai sebelumnya)
        var initialSkorJabatan = $('#posisi_id').find(':selected').data('skor');
        if (initialSkorJabatan) {
            $('#skor_jabatan').val(initialSkorJabatan);
            $('#form-skor-jabatan').show();
        }

        // Set bobot awal (jika ada nilai sebelumnya)
        calculateBobot();
    });
</script>
