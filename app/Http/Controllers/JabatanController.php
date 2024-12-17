<?php

namespace App\Http\Controllers;

use App\Models\JabatanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JabatanController extends Controller
{
    // Menampilkan halaman daftar level
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Jabatan',
            'list' => ['Home', 'Jabatan']
        ];
        $page = (object) [
            'title' => 'Daftar Jabatan yang terdaftar dalam sistem'
        ];
        $activeMenu = 'jabatan'; // set menu yang sedang aktif

        $jabatan = JabatanModel::all(); // ambil data level untuk filter jabatan

        return view('jabatan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'jabatan' => $jabatan, 'activeMenu' => $activeMenu]);
    }

    // Ambil data jabatan dalam bentuk JSON untuk datatables
    public function list(Request $request)
    {
        $jabatan = JabatanModel::select('id_jabatan', 'nama_jabatan', 'skor', 'IsPIC');

        // Filter data level berdasarkan id_jabatan
        if ($request->level_id) {
            $jabatan->where('level_id', $request->id_jabatan);
        }

        return DataTables::of($jabatan)
            ->addIndexColumn()
            ->addColumn('isPIC', function ($jabatan) {
                return $jabatan->isPIC ? '1' : '0';
            })
            // ->addColumn('aksi', function ($jabatan) { // menambahkan kolom aksi
            //     $btn = '<a href="' . url('/jabatan/' . $jabatan->id_jabatan) . '" class="btn btn-info btn-sm">Detail</a> ';
            //     $btn .= '<a href="' . url('/jabatan`/' .  $jabatan->id_jabatan . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
            //     $btn .= '<form class="d-inline-block" method="POST" action="' .
            //         url('/jabatan/' .  $jabatan->id_jabatan) . '">';
            //     //     . csrf_field() . method_field('DELETE') .
            //     //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
            //     return $btn;
            // })
            ->rawColumns(['isPIC'])
                      
            ->make(true);
    }

    // Menampilkan detail jabatan
    public function show(string $id)
    {
        $jabatan = JabatanModel::find($id);

        if (!$jabatan) {
            return redirect('/jabatan')->with('error', 'Data level tidak ditemukan');
        }

        $breadcrumb = (object) [
            'title' => 'Detail Jabatan',
            'list' => ['Home', 'Jabatan', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail Jabatan'
        ];
        $activeMenu = 'jabatan'; // set menu yang sedang aktif
        return view('jabatan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'jabatan' => $jabatan, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id)
    {
        $jabatan = JabatanModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit Jabatan',
            'list' => ['Home', 'Jabatan', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit Jabatan'
        ];
       
        $activeMenu = 'jabatan'; // set menu yang sedang aktif
        return view('jabatan.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'jabatan' => $jabatan, 'activeMenu' => $activeMenu]);
    }
}
