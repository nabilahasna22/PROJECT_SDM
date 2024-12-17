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
                    ->addIndexColumn()
                    ->addColumn('aksi', function ($row) {
                        // Ganti tombol aksi Edit dan Delete dengan Detail
                        return '<a href="' . route('statistik_dosen.detail', $row->nip) . '" class="text-info">[Detail]</a>';
                    })
                    ->rawColumns(['aksi']) // Render HTML untuk kolom aksi
                    ->make(true);
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
