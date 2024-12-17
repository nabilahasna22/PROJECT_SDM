<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KegiatanModel extends Model
{
    use HasFactory;

    protected $table = 'kegiatan'; // Nama tabel yang digunakan
    protected $primaryKey = 'kegiatan_id'; // Primary key dari tabel ini
    protected $fillable = ['kategori_id', 'kegiatan_nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'status', 'id_wilayah', 'periode_id','surat_tugas']; // Menambahkan periode_id ke fillable

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

    /**
     * Relasi ke PeriodeModel
     * Setiap KegiatanModel terkait dengan satu PeriodeModel
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeModel::class, 'periode_id', 'periode_id'); // mengacu pada periode_id
    }

    public function kegiatan(): HasMany
    {
        return $this->hasMany(UserModel::class, 'user_id', 'user_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailKegiatanModel::class, 'kegiatan_id', 'kegiatan_id');
    }
    public function agenda_progres()
    {
        return $this->hasMany(AgendaProgresModel::class, 'kegiatan_id', 'kegiatan_id');
    }
}
