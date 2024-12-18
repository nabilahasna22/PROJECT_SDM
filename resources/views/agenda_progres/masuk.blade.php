@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Progress Kegiatan: {{ $agendaProgres->kegiatan->kegiatan_id }}</h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form id="form-progress" method="POST" enctype="multipart/form-data" action="{{ url('agenda_progres/store') }}">
                @csrf
                <input type="hidden" name="kegiatan_id" value="{{ $agendaProgres->kegiatan->kegiatan_id }}">

                <div class="form-group">
                    <label for="progress">Status Progress</label>
                    <select name="progress" id="progress" class="form-control">
                        <option value="">- Pilih Status -</option>
                        <option value="not_started">Not Started</option>
                        <option value="on_progress">On Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="file_dokumen">Upload File Dokumen</label>
                    <input type="file" name="file_dokumen" id="file_dokumen" class="form-control">
                </div>

                <div class="form-group">
                    <label for="file_deskripsi">Deskripsi File</label>
                    <textarea name="file_deskripsi" id="file_deskripsi" class="form-control" rows="3" placeholder="Tulis deskripsi file di sini..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ url('agenda_progres') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            $('#form-progress').on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin menyimpan data ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData(this);

                        $.ajax({
                            url: $(this).attr('action'),
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                        window.location.href = "{{ url('agenda_progres') }}";
                                    });
                                } else {
                                    Swal.fire('Gagal!', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', 'Terjadi kesalahan: ' + xhr.responseJSON.message, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
