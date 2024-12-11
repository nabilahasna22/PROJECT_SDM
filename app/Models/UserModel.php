<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Casts\Attribute;

class UserModel extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'user'; // Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'nip'; // Mendefinisikan primary key dari tabel yang digunakan

    // Menyesuaikan kolom yang dapat diisi sesuai dengan kolom pada tabel
    protected $fillable = ['level_id','username', 'nama', 'email', 'password', 'no_telp', 'foto', 'alamat', 'created_at', 'updated_at'];

    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    // Relasi dengan model Level menggunakan kolom role
    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    //tambahan untuk gambar
    protected function image(): Attribute
    {
    return Attribute::make(
    get: fn ($image) => url('/storage/posts/' . $image),
    );
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return[];
    }
}
