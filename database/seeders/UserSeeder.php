<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use App\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Update password untuk user dengan NIP 123456789 	 dan username Dyahhh
        $user = UserModel::where('nip', '123456789')->where('username', 'Dyahhh')->first();

        if ($user) {
            $user->update([
                'password' => Hash::make('12345'), // Ganti dengan password baru
            ]);
            $this->command->info("Password user dengan NIP: 123456789 telah diperbarui.");
        } else {
            $this->command->warn("User dengan NIP: 123456789 dan username: Dyahhh tidak ditemukan.");
        }

        // 2. Tambahkan 9 data baru dengan level_id = 2
        $newUsers = [];
        for ($i = 1; $i <= 9; $i++) {
            $newUsers[] = [
                'nip' => '12345671' . $i, // NIP unik
                'username' => 'dosen_' . $i, // Username unik
                'nama' => 'Dosen ' . $i,
                'email' => 'dosen' . $i . '@example.com',
                'password' => Hash::make('password' . $i), // Password default
                'no_telp' => '0812345678' . $i, // Nomor telepon unik
                'foto' => null, // Kosongkan jika tidak ada foto
                'alamat' => 'Alamat Dosen ' . $i,
                'level_id' => 2, // Level dosen
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        UserModel::insert($newUsers);
        $this->command->info("9 data user baru dengan level dosen telah ditambahkan.");
    }
}