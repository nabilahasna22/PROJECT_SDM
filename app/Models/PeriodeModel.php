<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeModel extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi Laravel
    protected $table = 'periode_kegiatan';
    protected $primaryKey = 'periode_id';
    // Tentukan kolom yang dapat diisi
    protected $fillable = ['tahun'];

    /**
     * Relasi ke KegiatanModel
     * Setiap PeriodeModel dapat memiliki banyak KegiatanModel
     */
    public function kegiatan(): HasMany
    {
        return $this->hasMany(KegiatanModel::class, 'periode_id', 'periode_id'); // mengacu pada periode_id
    }
}
