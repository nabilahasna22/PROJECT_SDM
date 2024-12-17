<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKegiatanModel extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_anggota';
    protected $primaryKey = 'anggota_id';

    protected $fillable = [
        'anggota_id',
        'kegiatan_id',
        'nip',
        'id_jabatan',
        'bobot',
        'anggota_id'
    ];

    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'kegiatan_id', 'kegiatan_id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'nip', 'nip');
    }    

    public function jabatan()
    {
        return $this->belongsTo(JabatanModel::class, 'id_jabatan', 'id_jabatan');
    }

    public function wilayah()
{
    return $this->belongsTo(Wilayah::class, 'wilayah_id', 'wilayah_id');
}
}

