<?php

namespace App\Http\Controllers;

use App\Models\AgendaProgresModel;
use App\Models\ProgresModel;
use App\Models\KegiatanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProgresController extends Controller
{
    public function index()
    {
        $activeMenu = 'progres';
        $breadcrumb = (object) [
            'title' => 'Daftar Progres',
            'list' => ['Home', 'Progres']
        ];
    
        $page = (object) [
            'title' => 'Daftar progres kegiatan yang terdaftar dalam sistem'
        ];
    
        $kegiatan = KegiatanModel::select('kegiatan_id', 'kegiatan_nama')->get();
    
        return view('progres.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kegiatan' => $kegiatan
        ]);
    }
    
    public function list(Request $request)
    {
        $query = ProgresModel::with('kegiatan');
    
        return DataTables::of($query)
            ->addIndexColumn() // Tambahkan index otomatis
            ->addColumn('kegiatan_nama', function ($row) {
                return $row->kegiatan ? $row->kegiatan->kegiatan_nama : 'Tidak Ada Kegiatan';
            })
        
        
            ->make(true);
    }
    public function download($filename)
    {
        $filePath = public_path('uploads/dokumen/' . $filename);
    
        // Periksa apakah file ada di direktori tujuan
        if (File::exists($filePath)) {
            return response()->download($filePath);
        }
    
        abort(404, 'File not found');
    }
    
}    