<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Data untuk kategori_id 1 (JTI-Terprogram)
            [
                'kegiatan_nama'   => 'Seminar Teknologi Informasi',
                'kategori_id'     => 1,
                'id_wilayah'      => 3, // Jurusan
                'deskripsi'       => 'Seminar tentang perkembangan teknologi terkini.',
                'tanggal_mulai'   => '2024-12-10',
                'tanggal_selesai' => '2024-12-12',
                'status'          => 'terlaksana',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'kegiatan_nama'   => 'Pelatihan IoT',
                'kategori_id'     => 1,
                'id_wilayah'      => 4, // Prodi
                'deskripsi'       => 'Pelatihan pengenalan IoT untuk mahasiswa.',
                'tanggal_mulai'   => '2024-11-15',
                'tanggal_selesai' => '2024-11-16',
                'status'          => 'terlaksana',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'kegiatan_nama'   => 'Dialog Dosen dan Mahasiswa',
                'kategori_id'     => 1,
                'id_wilayah'      => 3, // Jurusan
                'deskripsi'       => 'Diskusi terbuka antara dosen dan mahasiswa.',
                'tanggal_mulai'   => '2024-12-01',
                'tanggal_selesai' => '2024-12-01',
                'status'          => 'terlaksana',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'kegiatan_nama'   => 'Lomba Karya Tulis Ilmiah',
                'kategori_id'     => 1,
                'id_wilayah'      => 4, // Prodi
                'deskripsi'       => 'Lomba karya tulis untuk mahasiswa TI.',
                'tanggal_mulai'   => '2024-11-20',
                'tanggal_selesai' => '2024-11-22',
                'status'          => 'on progres',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],

            // Data untuk kategori_id 2 (JTI-Nonprogram)
            [
                'kegiatan_nama'   => 'Bakti Sosial Mahasiswa',
                'kategori_id'     => 2,
                'id_wilayah'      => 3, // Jurusan
                'deskripsi'       => 'Kegiatan bakti sosial untuk masyarakat sekitar.',
                'tanggal_mulai'   => '2024-12-05',
                'tanggal_selesai' => '2024-12-05',
                'status'          => 'terlaksana',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'kegiatan_nama'   => 'Pelatihan Soft Skill',
                'kategori_id'     => 2,
                'id_wilayah'      => 4, // Prodi
                'deskripsi'       => 'Pelatihan pengembangan soft skill mahasiswa.',
                'tanggal_mulai'   => '2024-11-30',
                'tanggal_selesai' => '2024-11-30',
                'status'          => 'on progres',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'kegiatan_nama'   => 'Lomba Desain Poster',
                'kategori_id'     => 2,
                'id_wilayah'      => 3, // Jurusan
                'deskripsi'       => 'Lomba desain poster untuk mahasiswa JTI.',
                'tanggal_mulai'   => '2024-12-08',
                'tanggal_selesai' => '2024-12-10',
                'status'          => 'terlaksana',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'kegiatan_nama'   => 'Kuliah Umum Entrepreneurship',
                'kategori_id'     => 2,
                'id_wilayah'      => 4, // Prodi
                'deskripsi'       => 'Kuliah umum mengenai entrepreneurship.',
                'tanggal_mulai'   => '2024-12-01',
                'tanggal_selesai' => '2024-12-01',
                'status'          => 'on progres',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],

            // Data untuk kategori_id 3 (Non-JTI)
            [
                'kegiatan_nama'   => 'Pameran Teknologi',
                'kategori_id'     => 3,
                'id_wilayah'      => 1, // Luar Institusi
                'deskripsi'       => 'Pameran teknologi untuk masyarakat umum.',
                'tanggal_mulai'   => '2024-12-15',
                'tanggal_selesai' => '2024-12-16',
                'status'          => 'terlaksana',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'kegiatan_nama'   => 'Kegiatan Donor Darah',
                'kategori_id'     => 3,
                'id_wilayah'      => 2, // Pusat Polinema
                'deskripsi'       => 'Kegiatan donor darah untuk masyarakat.',
                'tanggal_mulai'   => '2024-11-25',
                'tanggal_selesai' => '2024-11-25',
                'status'          => 'on progres',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'kegiatan_nama'   => 'Pelatihan Keamanan Siber',
                'kategori_id'     => 3,
                'id_wilayah'      => 1, // Luar Institusi
                'deskripsi'       => 'Pelatihan keamanan siber untuk pelajar.',
                'tanggal_mulai'   => '2024-12-10',
                'tanggal_selesai' => '2024-12-12',
                'status'          => 'terlaksana',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'kegiatan_nama'   => 'Seminar Umum Pendidikan',
                'kategori_id'     => 3,
                'id_wilayah'      => 2, // Pusat Polinema
                'deskripsi'       => 'Seminar pendidikan untuk guru dan pelajar.',
                'tanggal_mulai'   => '2024-12-03',
                'tanggal_selesai' => '2024-12-04',
                'status'          => 'on progres',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ];

        DB::table('kegiatan')->insert($data);
    }
}
