<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgresModel extends Model
{
    use HasFactory;

    protected $table = 'progres'; // Define the table name used by this model
    protected $primaryKey = 'progres_id'; // Define the primary key for the table
    protected $fillable = ['kegiatan_id', 'nip', 'tanggal', 'deskripsi']; // Define mass-assignable fields

    /**
     * Relationship with Kegiatan model.
     * Each progress entry belongs to a specific activity (kegiatan).
     */
    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(KegiatanModel::class, 'kegiatan_id', 'kegiatan_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(userModel::class, 'nip', 'nip');
    }

}
