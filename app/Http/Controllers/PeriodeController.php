<?php

namespace App\Http\Controllers;

use App\Models\PeriodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PeriodeController extends Controller
{
    // Menampilkan halaman daftar periode kegiatan
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Periode Kegiatan',
            'list' => ['Home', 'Periode Kegiatan']
        ];
        $page = (object) [
            'title' => 'Daftar periode kegiatan internal JTI Polinema'
        ];
        $activeMenu = 'periode'; // set menu yang sedang aktif

        return view('periode.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Ambil data periode dalam bentuk JSON untuk datatables
    public function list(Request $request)
    {
        $periode = PeriodeModel::select('periode_id', 'tahun');
        return DataTables::of($periode)->make(true);
    }

    // Menampilkan form untuk membuat periode baru
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Periode',
            'list' => ['Home', 'Periode', 'Tambah']
        ];
        $page = (object) [
            'title' => 'Tambah Periode baru'
        ];
        $activeMenu = 'periode'; // set menu yang sedang aktif
        return view('periode.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
    public function create_ajax()
    {
        return view('periode.create_ajax');
    }
    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|date_format:Y', // Validasi tahun dalam format "YYYY"
        ]);
        PeriodeModel::create([
           'tahun' => $request->tahun,
        ]);
        return redirect('periode')->with('success', 'Data berhasil ditambahkan.'); 
    }
    public function store_ajax(Request $request)
{
    // Pastikan permintaan datang melalui AJAX atau menginginkan JSON
    if ($request->ajax() || $request->wantsJson()) {

        // Definisikan aturan validasi
        $rules = [
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 10)], // Tahun harus valid, antara 2000 hingga tahun sekarang + 10
        ];

        // Validasi input
        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal, kirimkan respons error
        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        // Jika validasi berhasil, simpan data periode ke dalam database
        PeriodeModel::create([
            'tahun' => $request->tahun, // Simpan tahun
        ]);

        // Kembalikan respons sukses
        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil disimpan'
        ]);
    }

    // Jika bukan AJAX, redirect ke halaman utama (optional)
    return redirect('/');
}

}
