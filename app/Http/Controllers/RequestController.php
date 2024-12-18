<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\KegiatanService;
use App\Http\Resources\KegiatanResource;
use App\Http\Resources\PendingApprovalResource;
use App\Models\DetailKegiatanModel;
use App\Models\JabatanModel;
use App\Models\KegiatanModel;
use App\Models\RequestModel;
use App\Models\UserModel;
use App\Models\Wilayah;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RequestController extends Controller
{
    public function index()
    {
        $activeMenu = 'request';
        $breadcrumb = (object) [
            'title' => 'Request Anggota',
            'list' => ['Home', 'Request Anggota']
        ];
        $page = (object) [
            'title' => 'Request Anggota'
        ];

        return view('request.index',['page'=>$page,'activeMenu'=>$activeMenu,'breadcrumb'=>$breadcrumb]);
    }

    // Mengambil data untuk DataTable (server-side)
    public function list(Request $request)
    {
        if ($request->ajax()) {
            // Query data dari tabel pending_approvals
            $data = KegiatanModel::with('kategori','wilayah','periode','detail','agenda_progres')->get();

            return DataTables::of($data)
                ->addIndexColumn() // Menambahkan kolom index
                ->addColumn('aksi', function ($data) {
                    $btn = '<a href="'.url('request/masuk/'.$data->kegiatan_id).'" class="edit btn btn-sm btn-primary">Masuk</a>';
                    return $btn;
                })
                ->rawColumns(['aksi']) // Mengizinkan HTML pada kolom action
                ->make(true);
        }
    }

    public function masuk($id){
        $breadcrumb = (object)[
            'title'=>'Tambah Anggota Kegiatan',
            'list'=>['Home','Tambah Anggota Kegiatan']
        ];

        $activeMenu = 'request';
        $kegiatan = KegiatanModel::with('kategori','wilayah','periode','detail','agenda_progres')->find($id);
        return view('request.masukkegiatan',['breadcrumb'=>$breadcrumb,'activeMenu'=>$activeMenu,'kegiatan'=>$kegiatan]);
    }

    public function listanggota(Request $request, $id){
        $anggota = RequestModel::with('dosen','kegiatan','jabatan')->where('kegiatan_id',$id)->get();

        if ($request->posisi_id){
            $anggota->where('posisi_id',$request->posisi_id);
        }

        return DataTables::of($anggota)
            ->addIndexColumn()
            ->addColumn('aksi', function ($anggota) { 
                $btn  = '<button onclick="modalAction(\'' . url('/request/' . $anggota->id . '/confirm') . '\')" class="btn btn-danger btn-sm">Batalkan</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) 
            ->make(true);
    }

    public function create_ajax($id) {
        // Ambil kegiatan sesuai ID
        $kegiatan = KegiatanModel::with('wilayah')->findOrFail($id);
    
        $dosen = UserModel::where('level_id', 2)->get();
        $jabatan = JabatanModel::where('isPIC', 0)->get();
        $wilayah = Wilayah::where('id_wilayah', $kegiatan->id_wilayah)->get();
    
        // Ambil skor wilayah
        $skor = $kegiatan->wilayah ? $kegiatan->wilayah->skor : null;
    
        return view('request.create_ajax', [
            'dosen' => $dosen,
            'jabatan' => $jabatan,
            'wilayah' => $wilayah,
            'kegiatan' => $kegiatan,
            'skor' => $skor // Kirim skor ke view
        ]);
    }    

    public function ajax(Request $request) {
        $validator = Validator::make($request->all(), [
            'dosen_nip' => 'required|string',
            'kegiatan_id' => 'required|integer',
            'posisi_id' => 'required|integer',
            'skor_wilayah' => 'required|integer',
            'skor_jabatan' => 'required|integer',
            'bobot' => 'required|integer', // Validasi untuk bobot
            'status' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            // Simpan ke database
            RequestModel::create([
                'dosen_nip' => $request->dosen_nip,
                'kegiatan_id' => $request->kegiatan_id,
                'posisi_id' => $request->posisi_id,
                'skor_wilayah' => $request->skor_wilayah,
                'skor_jabatan' => $request->skor_jabatan,
                'bobot' => $request->bobot, // Menyimpan nilai bobot
                'status' => $request->status
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Data Anggota berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function confirm($id){
        $user = RequestModel::with('kegiatan','dosen','jabatan')->find($id);
        return view('request.confirm_ajax',['user'=>$user]);
    }

    public function delete($id)
{
    // Cari data user berdasarkan ID
    $user = RequestModel::find($id);

    // Cek apakah data ditemukan
    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan.'
        ]);
    }

    // Periksa status, hanya status 'pending' yang bisa dihapus
    if ($user->status !== 'pending') {
        return response()->json([
            'status' => false,
            'message' => 'Hanya data dengan status pending yang bisa dihapus.'
        ]);
    }

    // Jika status pending, hapus data
    $user->delete();

    // Beri respons berhasil
    return response()->json([
        'status' => true,
        'message' => 'Data berhasil dihapus.'
    ]);
}

}