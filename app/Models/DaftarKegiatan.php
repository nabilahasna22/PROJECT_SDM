<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DaftarKegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan'; // Nama tabel yang digunakan
    protected $primaryKey = 'kegiatan_id'; // Primary key dari tabel ini
    protected $fillable = ['kategori_id', 'kegiatan_nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'status', 'id_wilayah']; // Menambahkan id_wilayah ke fillable

    /**
     * Relasi ke KategoriModel
     * Setiap KegiatanModel memiliki satu KategoriModel
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }

    /**
     * Relasi ke WilayahModel
     * Setiap KegiatanModel terkait dengan satu WilayahModel
     */
    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id_wilayah');
    }

    public function kegiatan(): HasMany
    {
        return $this->hasMany(UserModel::class, 'user_id', 'user_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailKegiatanModel::class, 'kegiatan_id', 'kegiatan_id');
    }
}
