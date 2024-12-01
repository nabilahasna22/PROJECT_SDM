<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LevelModel extends Model
{
    use HasFactory;

    protected $table = 'level'; // Tabel yang digunakan
    protected $primaryKey = 'level_id'; // Primary key tabel

    protected $fillable = ['level_kode', 'level_nama']; // Kolom yang dapat diisi

    // Relasi dengan UserModel
    public function user(): HasMany 
    { 
        return $this->hasMany(UserModel::class, 'level_id', 'level_id'); 
    }
}
