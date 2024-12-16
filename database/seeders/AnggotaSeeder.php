<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnggotaSeeder extends Seeder
{
    public function run()
    {
        
        $data = [
            // Hubungkan dengan ID kegiatan yang ada (asumsi ID kegiatan mulai dari 1)
            [
                'kegiatan_id' => 1,
                'nip'         => '987654321',
                'id_jabatan'  => 1, // Contoh jabatan (sesuaikan dengan ID jabatan yang ada di sistem Anda)
                'bobot'       => 5, // Nilai bobot
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'kegiatan_id' => 2,
                'nip'         => '987654321',
                'id_jabatan'  => 1,
                'bobot'       => 7,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'kegiatan_id' => 3,
                'nip'         => '987654321',
                'id_jabatan'  => 3,
                'bobot'       => 6,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'kegiatan_id' => 4,
                'nip'         => '987654321',
                'id_jabatan'  => 1,
                'bobot'       => 8,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'kegiatan_id' => 8,
                'nip'         => '987654321',
                'id_jabatan'  => 4,
                'bobot'       => 4,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'kegiatan_id' => 9,
                'nip'         => '987654321',
                'id_jabatan'  => 1,
                'bobot'       => 9,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'kegiatan_id' => 10,
                'nip'         => '987654321',
                'id_jabatan'  => 3,
                'bobot'       => 7,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'kegiatan_id' => 11,
                'nip'         => '987654321',
                'id_jabatan'  => 4,
                'bobot'       => 5,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'kegiatan_id' => 12,
                'nip'         => '987654321',
                'id_jabatan'  => 3,
                'bobot'       => 10,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'kegiatan_id' => 13,
                'nip'         => '987654321',
                'id_jabatan'  => 4,
                'bobot'       => 6,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        DB::table('kegiatan_anggota')->insert($data);
    }
}
