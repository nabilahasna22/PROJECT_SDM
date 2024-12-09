<form action="{{ url('/periode/store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Periode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="tahun">Tahun</label>
                    <input type="number" class="form-control" id="tahun" name="tahun" required
                           placeholder="Masukkan Tahun (2000 ke atas)" min="2000">
                    <small id="error-tahun" class="error-text form-text text-danger"></small>
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
        $('#tahun').on('input', function() {
            var input = $(this).val();
            var currentYear = new Date().getFullYear();

            if (input < 2000 || input > currentYear + 10) { // Batas fleksibel hingga 10 tahun ke depan
                $('#error-tahun').text('Tahun harus antara 2000 hingga ' + (currentYear + 10) + '.');
                $(this).addClass('is-invalid');
            } else {
                $('#error-tahun').text('');
                $(this).removeClass('is-invalid');
            });
        // Validasi dan AJAX Submit
        $("#form-tambah").validate({
            rules: {
                tahun: {
                    required: true
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#modal-master').modal('hide'); // Sembunyikan modal
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            // Reload data tabel jika ada
                            if (typeof tablePeriode !== 'undefined') {
                                tablePeriode.ajax.reload();
                            }
                        } else {
                            // Reset pesan error
                            $('.error-text').text('');
                            // Tampilkan error validasi jika ada
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan pada server.'
                        });
                    }
                });
                return false; // Prevent default submit
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
</script>
