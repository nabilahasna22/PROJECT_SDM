<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailProgresModel extends Model
{
    use HasFactory;

    protected $table = 'detail_progres';
    protected $primaryKey = 'detail_id';
    protected $fillable = [
        'progres_id',      // Foreign key to Progres
        'lampiran'         // Path to the attachment file
    ];

    // Relationship with Progres
    public function progres()
    {
        return $this->belongsTo(ProgresModel::class, 'progres_id', 'progres_id');
    }
}
