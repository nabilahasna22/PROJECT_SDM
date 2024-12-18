<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agenda Progress</title>
    <!-- Link Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5>Edit Agenda Progress</h5>
            </div>
            <div class="card-body">
                <!-- Form -->
                <form>
                    <!-- Kegiatan -->
                    <div class="mb-3">
                        <label for="kegiatan" class="form-label">Kegiatan</label>
                        <select id="kegiatan" class="form-select">
                            <option selected>Dialog Dosen Mahasiswa JTI</option>
                            <option>Workshop</option>
                            <option>Seminar</option>
                        </select>
                    </div>

                    <!-- NIP -->
                    <div class="mb-3">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" id="nip" class="form-control" value="987654321" readonly>
                    </div>

                    <!-- File Dokumen -->
                    <div class="mb-3">
                        <label for="file" class="form-label">File Dokumen</label>
                        <input type="file" class="form-control" id="file">
                        <a href="#" class="mt-2 d-inline-block">Lihat Dokumen</a>
                    </div>

                    <!-- Keterangan File -->
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan File</label>
                        <input type="text" id="keterangan" class="form-control" value="Pengajuan Dana">
                    </div>

                    <!-- Status Progress -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Progress</label>
                        <select id="status" class="form-select">
                            <option selected>Completed</option>
                            <option>In Progress</option>
                            <option>Pending</option>
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Link Bootstrap 5 JS Bundle (Optional for Dropdown, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
