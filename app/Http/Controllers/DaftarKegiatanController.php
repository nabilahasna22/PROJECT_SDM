<?php
namespace App\Http\Controllers;

use App\Models\KegiatanModel;
use App\Models\KategoriModel;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DaftarKegiatanController extends Controller
{
    public function index()
    {
    $activeMenu = 'daftar_kegiatan';
    $breadcrumb = (object)[
        'title' => 'Data Kegiatan',
        'list'  => ['Home', 'Kegiatan']
    ];

    // Mengambil data kategori dan wilayah
    $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
    $wilayah = Wilayah::select('id_wilayah', 'nama_wilayah')->get();

    // Mengirim semua data ke view
    return view('daftar_kegiatan.index', [
        'activeMenu'  => $activeMenu,
        'breadcrumb'  => $breadcrumb,
        'kategori'    => $kategori,
        'wilayah'     => $wilayah
    ]);
    }

    public function list(Request $request)
    {
        $daftar_kegiatan = KegiatanModel::select('kategori_id', 'kegiatan_id', 'kegiatan_nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'status', 'id_wilayah')
            ->with(['kategori', 'wilayah']); // Tambahkan relasi 'wilayah'
    
        $kategori_id = $request->input('filter_kategori');
        if (!empty($kategori_id)) {
            $daftar_kegiatan->where('kategori_id', $kategori_id);
        }
        $id_wilayah = $request->input('filter_wilayah');
        if (!empty($id_wilayah)) {
            $daftar_kegiatan->where('id_wilayah', $id_wilayah);
        }
    
        return DataTables::of($daftar_kegiatan);
            
    }
}