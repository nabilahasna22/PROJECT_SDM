<?php

namespace App\Http\Controllers;

use App\Models\DetailKegiatanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DetailKegiatanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Detail Kegiatan',
            'list' => ['Home', 'Detail Kegiatan']
        ];

        $page = (object) [
            'title' => 'Daftar detail kegiatan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'detailkegiatan';

        return view('detailkegiatan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }
    public function list(Request $request)
    {
        $detailKegiatan = DetailKegiatanModel::select('id', 'kegiatan_id', 'nip', 'jabatan', 'bobot')
            ->with(['kegiatan', 't_user']); // Asumsi bahwa ada relasi dengan tabel kegiatan dan t_user
    
        $kategori_id = $request->input('filter_kategori');
        if (!empty($kategori_id)) {
            $detailKegiatan->whereHas('kegiatan', function($query) use ($kategori_id) {
                $query->where('kategori_id', $kategori_id);
            });
        }
    
        return DataTables::of($detailKegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($detailKegiatan) {
                $btn = '<a href="' . url('/detailkegiatan/' . $detailKegiatan->id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<button onclick="modalAction(\''.url('/detailkegiatan/'. $detailKegiatan->id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button>';
                $btn .= '<button onclick="modalAction(\''.url('/detailkegiatan/' . $detailKegiatan->id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    

}
