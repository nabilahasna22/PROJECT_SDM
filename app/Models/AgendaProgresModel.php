<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendaProgresModel extends Model
{
    protected $table = 'agenda_progres';
    protected $primaryKey = 'id_progres';
    protected $fillable = [
        'kegiatan_id',
        'nip',
        'file_dokumen',
        'file_deskripsi',
        'progress' // Gunakan 'progress'
    ];

    // Relationship with Kegiatan model
    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'kegiatan_id', 'kegiatan_id');
    }

    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'nip', 'nip');
    }
}