<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Pengguna</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Tambahkan style untuk logo */
        
        .login-logo {
            margin-top: 20px; /* Menambahkan jarak di atas logo */
            margin-bottom: 1px;
        }

        .login-logo img {
            width: 200px; /* Sesuaikan ukuran logo */
            height: auto;
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="login-logo">
                <img src="{{ asset('image/polinemabg.png') }}" alt="Logo">
            </div>
            <div class="card-header text-center"><a href="{{ url('/') }}" class="h1"><b>SIMTI</b></a></div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="{{ url('login') }}" method="POST" id="form-login">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" id="nip" name="nip" class="form-control" placeholder="NIP">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-id-badge"></span>
                            </div>
                        </div>
                        <small id="error-nip" class="error-text text-danger"></small>
                    </div>
                    <div class="input-group mb-3 position-relative">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <small id="error-password" class="error-text text-danger"></small>
                    
                        <!-- Tombol Tampilkan/Sembunyikan dengan ikon -->
                        <button type="button" id="toggle-password" class="btn btn-link" style="position: absolute; right: 35px; top: 50%; transform: translateY(-50%); display: none;">
                            <i class="fas fa-eye"></i> <!-- Ikon mata untuk menampilkan -->
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">Remember Me</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                    <hr>
                </form>
            </div>
            <footer class="text-center mt-3">
                <small>2024 &copy; Sistem Informasi Manajemen SDM TI<small>
            </footer>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#password').on('input', function() {
            if ($(this).val().length > 0) {
                $('#toggle-password').show(); // Tampilkan tombol
            } else {
                $('#toggle-password').hide(); // Sembunyikan tombol jika kolom kosong
            }
        });
        // Fungsi untuk toggle password
        $('#toggle-password').on('click', function () {
            const passwordInput = $('#password');
            const toggleButton = $(this);

            // Toggle tipe input antara 'password' dan 'text'
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                toggleButton.html('<i class="fas fa-eye-slash"></i>'); // Ikon mata ter-coret untuk menyembunyikan
            } else {
                passwordInput.attr('type', 'password');
                toggleButton.html('<i class="fas fa-eye"></i>'); // Ikon mata untuk menampilkan
            }
        });
            $("#form-login").validate({
                rules: {
                    nip: {
                        required: true,
                        minlength: 4,
                        maxlength: 20
                    },
                    password: {
                        required: true,
                        minlength: 5,
                        maxlength: 20
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) { // jika sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                }).then(function() {
                                    window.location = response.redirect;
                                });
                            } else { // jika error
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.input-group').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
</body>
</html>
