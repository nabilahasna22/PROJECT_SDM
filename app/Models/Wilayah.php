<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari nama model dalam bentuk jamak
    protected $table = 'wilayah_kegiatan';

    // Tentukan primary key jika tidak menggunakan id
    protected $primaryKey = 'id_wilayah';

    // Jika primary key bukan auto-increment
    public $incrementing = true;

    // Tentukan tipe data untuk primary key
    protected $keyType = 'int';

    // Tentukan atribut yang bisa diisi secara mass-assignment
    protected $fillable = [
        'nama_wilayah',
        'skor',
    ];

    // Jika tabel tidak menggunakan kolom timestamps (created_at dan updated_at)
    public $timestamps = true;

    // Tambahkan relasi jika dibutuhkan
    // Contoh: Wilayah memiliki banyak cabang
    // public function cabang()
    // {
    //     return $this->hasMany(Cabang::class, 'id_wilayah', 'id_wilayah');
    // }
}
