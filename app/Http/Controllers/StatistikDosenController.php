<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KegiatanModel;
use Yajra\DataTables\Facades\DataTables;

class StatistikDosenController extends Controller
{
    // Menampilkan halaman statistik dosen
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Statistik Dosen',
            'list' => ['Home', 'Statistik Dosen']
        ];
        $page = (object) [
            'title' => 'Daftar statistik dosen yang terdaftar dalam sistem'
        ];
        $activeMenu = 'statistik_dosen'; // set menu yang sedang aktif

        return view('statistik_dosen.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Ambil data statistik dosen dalam bentuk JSON untuk datatables
    public function list(Request $request)
    {
        try {
            if ($request->ajax()) {
                // Ambil dosen berdasarkan level_id melalui LevelModel
                $data = User::with('level') // Relasi ke LevelModel
                    ->whereHas('level', function ($query) {
                        $query->where('id', 2); // level_id = 2
                    })
                    ->select('nip', 'nama as nama_dosen')
                    ->get();
    
                $data = $data->map(function ($item) {
                    // Total kegiatan yang diikuti oleh dosen
                    $item->total_kegiatan = \App\Models\KegiatanAnggota::where('nip', $item->nip)->count();
    
                    // Hitung kegiatan terprogram, non program, dan non JTI berdasarkan kategori_id
                    $item->terprogram = \App\Models\KegiatanAnggota::where('nip', $item->nip)
                        ->whereHas('kegiatan', function ($query) {
                            $query->where('kategori_id', 1); // kategori_id = 1 (terprogram)
                        })->count();
    
                    $item->non_program = \App\Models\KegiatanAnggota::where('nip', $item->nip)
                        ->whereHas('kegiatan', function ($query) {
                            $query->where('kategori_id', 2); // kategori_id = 2 (non program)
                        })->count();
    
                    $item->non_jti = \App\Models\KegiatanAnggota::where('nip', $item->nip)
                        ->whereHas('kegiatan', function ($query) {
                            $query->where('kategori_id', 3); // kategori_id = 3 (non jti)
                        })->count();
    
                    // Hitung total bobot berdasarkan kegiatan yang diikuti oleh dosen
                    $item->total_bobot = \App\Models\KegiatanAnggota::where('nip', $item->nip)
                        ->sum('bobot'); // Total bobot diambil dari kolom bobot di tabel kegiatan_anggota
    
                    return $item;
                });
    
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->make(true);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}    