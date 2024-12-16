<?php

namespace App\Http\Controllers;

use App\Models\DetailKegiatanModel;
use App\Models\JabatanModel;
use App\Models\KegiatanModel;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DetailKegiatanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kegiatan Anggota',
            'list' => ['Home', 'Kegiatan Anggota']
        ];

        $page = (object) [
            'title' => 'Daftar Anggota Kegiatan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'detailkegiatan';
        $kegiatan = KegiatanModel::all();
        $nip = UserModel::select('nip')->get();
        $jabatan = JabatanModel::select('id_jabatan', 'nama_jabatan')->get();


        return view('detailkegiatan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kegiatan' => $kegiatan,
            'nip'   => $nip,
            'jabatan' => $jabatan,
        ]);
    }

    public function list(Request $request)
    {
        $detailKegiatan = DetailKegiatanModel::select('detail_id', 'kegiatan_id', 'nip', 'id_jabatan', 'bobot')
            ->with(['kegiatan', 'user', 'jabatan']);

        return DataTables::of($detailKegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                return '
                    <div class="btn-group">
                        <button onclick="modalAction(\''.url('/detailkegiatan/edit/').'/'.$row->detail_id.'\')" class="btn btn-sm btn-warning">Edit</button>
                        <button onclick="modalAction(\''.url('/detailkegiatan/show_ajax/').'/'.$row->detail_id.'\')" class="btn btn-sm btn-info">Detail</button>               
                    </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show_ajax($id)
    {
        $detailKegiatan = DetailKegiatanModel::with(['kegiatan', 'user', 'jabatan'])
            ->where('detail_id', $id)
            ->first();

        if (!$detailKegiatan) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }

        return view('detailkegiatan.show_ajax', compact('detailKegiatan'));
    }



    public function create_ajax()
    {
        $kegiatan = KegiatanModel::all(); // Mengambil semua data kegiatan
        $user = User::all(); // Mengambil semua data user

        return view('detailkegiatan.create_ajax', compact('kegiatan', 'user'));
    }

    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kegiatan_id' => 'required|exists:kegiatan,kegiatan_id',
            'nip' => 'required|exists:user,nip',
            'id_jabatan' => 'required',
            'bobot' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }
        DetailKegiatanModel::create([
            'kegiatan_id' => $request->kegiatan_id,
            'nip' => $request->nip,
            'id_jabatan' => $request->id_jabatan,
            'bobot' => $request->bobot,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data berhasil ditambahkan.']);
    }
}

