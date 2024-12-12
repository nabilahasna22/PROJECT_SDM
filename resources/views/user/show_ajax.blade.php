@if($user)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Detail Informasi</h5>
                </div>
                <table class="table table-sm table-bordered table-striped">
                    {{-- <div class="col-md-4">
                        @if($fotoUrl)
                            <img src="{{ $fotoUrl }}" alt="Foto Profil" class="img-fluid rounded-circle">
                        @else
                            <p class="text-center">Tidak ada foto profil</p>
                        @endif
                    </div> --}}
                    <tr>
                        <th class="text-right col-3">ID User :</th>
                        <td class="col-9">{{ $user->level_id }}</td> <!-- Menggunakan level_id sebagai ID -->
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIP :</th>
                        <td class="col-9">{{ $user->nip }}</td> <!-- Menampilkan NIP -->
                    </tr>
                    <tr>
                        <th class="text-right col-3">Username :</th>
                        <td class="col-9">{{ $user->username }}</td> <!-- Menampilkan Username -->
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama :</th>
                        <td class="col-9">{{ $user->nama }}</td> <!-- Menampilkan Nama -->
                    </tr>
                    <tr>
                        <th class="text-right col-3">Email :</th>
                        <td class="col-9">{{ $user->email }}</td> <!-- Menampilkan Email -->
                    </tr>
                    <tr>
                        <th class="text-right col-3">No Telepon :</th>
                        <td class="col-9">{{ $user->no_telp }}</td> <!-- Menampilkan No Telepon -->
                    </tr>
                    <tr>
                        <th class="text-right col-3">Alamat :</th>
                        <td class="col-9">{{ $user->alamat }}</td> <!-- Menampilkan Alamat -->
                    </tr>
                    <tr>
                        <th class="text-right col-3">Level :</th>
                        <td class="col-9">{{ $user->level->level_nama ?? '-' }}</td> <!-- Menampilkan nama level yang terhubung -->
                    </tr>
                    <tr>
                        <th class="text-right col-3">Password :</th>
                        <td class="col-9">********</td> <!-- Password disembunyikan -->
                    </tr>
                    <tr>
                        <th class="text-right col-3">Foto Profil :</th>
                        <td class="col-9">
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Profile Picture" class="img-fluid" style="max-width: 150px; height: auto;">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@else
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
                <a href="{{ url('/user/') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@endif