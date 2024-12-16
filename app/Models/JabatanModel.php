<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JabatanModel extends Model
{
    // Nama tabel yang sesuai dengan database
    protected $table = 'jabatan';

    // Primary key tabel
    protected $primaryKey = 'id_jabatan';

    // Kolom-kolom yang dapat diisi (mass assignment)
    protected $fillable = [
        'nama_jabatan', 
        'skor', 
        'isPIC'
    ];

    // Cast tipe data untuk kolom tertentu
    protected $casts = [
        'isPIC' => 'boolean',
        'skor' => 'integer'
    ];

    // Relasi dengan kegiatan anggota
    public function detailKegiatan()
    {
        return $this->hasMany(DetailKegiatanModel::class, 'id_jabatan', 'id_jabatan');
    }

    // Scope untuk filter jabatan yang adalah PIC
    public function scopePIC($query)
    {
        return $query->where('isPIC', true);
    }

    // Getter untuk mendapatkan label PIC
    public function getPICLabelAttribute()
    {
        return $this->isPIC ? '1' : '0';
    }
}