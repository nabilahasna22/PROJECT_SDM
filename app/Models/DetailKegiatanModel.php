<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKegiatanModel extends Model
{
    use HasFactory;

    protected $table = 'detail_kegiatan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kegiatan_id',   // Foreign key to Kegiatan
        'nip',           // Foreign key to User's NIP
        'jabatan',       // Job position
        'bobot'          // Weight or percentage of the activity
    ];

    // Relationship with Kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'kegiatan_id', 'kegiatan_id');
    }

    // Relationship with User
    public function t_user()
    {
        return $this->belongsTo(User::class, 'nip', 'nip');
    }
}
