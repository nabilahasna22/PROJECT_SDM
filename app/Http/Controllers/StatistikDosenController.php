<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatistikDosenModel;
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
                // Ambil data statistik dosen dari model StatistikDosen
                $data = StatistikDosenModel::getDataDosen();
    
                return DataTables::of($data)
                    ->addIndexColumn() // Tambahkan indeks untuk setiap baris
                    ->make(true); // Hilangkan kolom 'aksi'
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }    

    // Menampilkan detail statistik dosen
    public function detail($nip)
    {
        $dosen = StatistikDosenModel::findOrFail($nip);
        return view('statistik_dosen.detail', compact('dosen'));
    }
}
