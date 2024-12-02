<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KegiatanSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan data kegiatan tanpa jenis_kegiatan, menambahkan id_wilayah sebagai FK
        DB::table('kegiatan')->insert([
            [
                'kategori_id' => 1,
                'kegiatan_nama' => 'Seminar Nasional AI',
                'deskripsi' => 'Seminar yang membahas perkembangan AI di Indonesia.',
                'tanggal_mulai' => '2024-11-01',
                'tanggal_selesai' => '2024-11-02',
                'status' => 'on progres',
                'id_wilayah' => 1, // id_wilayah sebagai FK dari tabel wilayah_kegiatan
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategori_id' => 2,
                'kegiatan_nama' => 'Workshop Pengembangan Web',
                'deskripsi' => 'Workshop untuk meningkatkan keterampilan pengembangan web bagi mahasiswa.',
                'tanggal_mulai' => '2024-11-05',
                'tanggal_selesai' => '2024-11-06',
                'status' => 'terlaksana',
                'id_wilayah' => 2, // id_wilayah sebagai FK dari tabel wilayah_kegiatan
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategori_id' => 3,
                'kegiatan_nama' => 'Pelatihan Desain Grafis',
                'deskripsi' => 'Pelatihan dasar-dasar desain grafis menggunakan perangkat lunak populer.',
                'tanggal_mulai' => '2024-11-10',
                'tanggal_selesai' => '2024-11-11',
                'status' => 'on progres',
                'id_wilayah' => 3, // id_wilayah sebagai FK dari tabel wilayah_kegiatan
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
