<?php

namespace App\Http\Controllers;

use App\Models\Wilayah; // Ganti LevelModel dengan Wilayah
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class WilayahController extends Controller
{
    // Menampilkan halaman daftar wilayah
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Wilayah',
            'list' => ['Home', 'Wilayah']
        ];
        $page = (object) [
            'title' => 'Daftar wilayah yang terdaftar dalam sistem'
        ];
        $activeMenu = 'wilayah'; // Set menu aktif

        $wilayah = Wilayah::all(); // Ambil data wilayah

        return view('wilayah.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'wilayah' => $wilayah, 'activeMenu' => $activeMenu]);
    }

    // Ambil data wilayah dalam bentuk JSON untuk datatables
    public function list(Request $request)
    {
        $wilayahs = Wilayah::select('id_wilayah', 'nama_wilayah', 'skor');

        // Filter data wilayah berdasarkan id_wilayah
        if ($request->id_wilayah) {
            $wilayahs->where('id_wilayah', $request->id_wilayah);
        }

        return DataTables::of($wilayahs)
            ->addIndexColumn()
            ->addColumn('aksi', function ($wilayah) { // Menambahkan kolom aksi
                $btn = '<a href="' . url('/wilayah/' . $wilayah->id_wilayah) . '" class="btn btn-info btn-sm">Detail</a> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // Kolom aksi berupa HTML
            ->make(true);
    }

    // Menampilkan detail wilayah
    public function show(string $id)
    {
        $wilayah = Wilayah::find($id);

        if (!$wilayah) {
            return redirect('/wilayah')->with('error', 'Data wilayah tidak ditemukan');
        }

        $breadcrumb = (object) [
            'title' => 'Detail Wilayah',
            'list' => ['Home', 'Wilayah', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail wilayah'
        ];
        $activeMenu = 'wilayah';
        return view('wilayah.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'wilayah' => $wilayah, 'activeMenu' => $activeMenu]);
    }
}
