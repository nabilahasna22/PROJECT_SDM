<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    use HasFactory;
    protected $table = 'pending_approval';

    // Kolom yang bisa diisi (fillable)
    protected $fillable = [
        'kegiatan_id',
        'dosen_nip',
        'posisi_id',
        'bobot',
        'status',
        'created_at',
    ];

    // Kolom primary key
    protected $primaryKey = 'id';

    // Timestamp
    public $timestamps = false;

    /**
     * Relasi ke tabel Kegiatan
     */
    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'kegiatan_id', 'kegiatan_id');
    }

    /**
     * Relasi ke tabel User (dosen)
     */
    public function dosen()
    {
        return $this->belongsTo(UserModel::class, 'dosen_nip', 'nip');
    }

    /**
     * Relasi ke tabel Jabatan (posisi)
     */
    public function jabatan()
    {
        return $this->belongsTo(JabatanModel::class, 'posisi_id', 'id_jabatan');
    }

   
}
