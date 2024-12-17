<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StatistikDosenModel extends Model
{
    protected $table = 'user'; // Tentukan tabel yang digunakan

    // Method untuk mengambil data statistik dosen
    public static function getDataDosen()
    {
        return DB::table('user')
            ->join('kegiatan_anggota', 'kegiatan_anggota.nip', '=', 'user.nip')
            ->join('kegiatan', 'kegiatan.kegiatan_id', '=', 'kegiatan_anggota.kegiatan_id')
            ->where('user.level_id', 2) // Pastikan level_id = 2 (Dosen)
            ->select('user.nip', 'user.nama as nama_dosen')
            ->selectRaw('
                COUNT(kegiatan_anggota.kegiatan_id) AS total_kegiatan,
                SUM(CASE WHEN kegiatan.kategori_id = 1 THEN 1 ELSE 0 END) AS terprogram,
                SUM(CASE WHEN kegiatan.kategori_id = 2 THEN 1 ELSE 0 END) AS non_program,
                SUM(CASE WHEN kegiatan.kategori_id = 3 THEN 1 ELSE 0 END) AS non_jti,
                SUM(kegiatan_anggota.bobot) AS total_bobot
            ')
            ->groupBy('user.nip', 'user.nama')
            ->get();
    }
}
