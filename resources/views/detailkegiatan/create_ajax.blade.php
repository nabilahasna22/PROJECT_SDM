<div class="modal-header">
    <h5 class="modal-title">Tambah Detail Kegiatan</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <form id="form-create">
        @csrf
        <div class="mb-3">
            <label for="kegiatan_id" class="form-label">Kegiatan</label>
            <select name="kegiatan_id" id="kegiatan_id" class="form-control" onchange="updateBobot()">
                <option value="">Pilih Kegiatan</option>
                @foreach ($kegiatan as $item)
                    <option value="{{ $item->kegiatan_id }}" data-wilayah="{{ $item->wilayah }}">{{ $item->nama_kegiatan }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <select name="nip" id="nip" class="form-control">
                <option value="">Pilih User</option>
                @foreach ($user as $item)
                    <option value="{{ $item->nip }}">{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="id_jabatan" class="form-label">Jabatan</label>
            <select name="id_jabatan" id="id_jabatan" class="form-control" onchange="updateBobot()">
                <option value="">Pilih Jabatan</option>
                @foreach ($jabatan as $item)
                    <option value="{{ $item->id_jabatan }}" data-bobot="{{ $item->bobot }}">{{ $item->nama_jabatan }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="bobot" class="form-label">Bobot</label>
            <input type="number" name="bobot" id="bobot" class="form-control" readonly>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-primary" onclick="saveData()">Simpan</button>
</div>
