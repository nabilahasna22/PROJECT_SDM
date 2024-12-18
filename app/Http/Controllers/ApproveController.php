<?php

namespace App\Http\Controllers;

use App\Models\KegiatanModel;
use App\Models\RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ApproveController extends Controller
{
    public function index()
    {
        $activeMenu = 'approve_anggota';
        $breadcrumb = (object) [
            'title' => 'Approve Anggota',
            'list' => ['Home', 'Approve Anggota']
        ];
        $page = (object) [
            'title' => 'ApproveAnggota'
        ];

        return view('approve.index',['page'=>$page,'activeMenu'=>$activeMenu,'breadcrumb'=>$breadcrumb]);
    }

     public function list(Request $request)
    {
        if ($request->ajax()) {
            // Query data dari tabel pending_approvals
            $data = KegiatanModel::with('kategori','wilayah','periode','detail','agenda_progres')->get();

            return DataTables::of($data)
                ->addIndexColumn() // Menambahkan kolom index
                ->addColumn('aksi', function ($data) {
                    $btn = '<a href="'.url('approve_anggota/masuk/'.$data->kegiatan_id).'" class="edit btn btn-sm btn-primary">Masuk</a>';
                    return $btn;
                })
                ->rawColumns(['aksi']) // Mengizinkan HTML pada kolom action
                ->make(true);
        }
    }
    public function masuk($id){
        $breadcrumb = (object)[
            'title'=>'Approve Anggota Kegiatan',
            'list'=>['Home','Approve Anggota Kegiatan']
        ];

        $activeMenu = 'approve_anggota';
        $kegiatan = KegiatanModel::with('kategori','wilayah','periode','detail','agenda_progres')->find($id);
        return view('approve.masukkegiatan',['breadcrumb'=>$breadcrumb,'activeMenu'=>$activeMenu,'kegiatan'=>$kegiatan]);
    }

    public function listanggota(Request $request, $id)
    {
        $anggota = RequestModel::with('dosen', 'kegiatan', 'jabatan')->where('kegiatan_id', $id)->where('status', 'pending')->get();
        if ($request->posisi_id) {
            $anggota->where('posisi_id', $request->posisi_id);
        }

        return DataTables::of($anggota)
            ->addIndexColumn()
            ->addColumn('aksi', function ($anggota) {
                $btnAccept = '<a href="' . url('approve_anggota/accept/' . $anggota->id) . '" class="btn btn-sm btn-success">Accept</a>';
                $btnDecline = '<a href="' . url('approve_anggota/decline/' . $anggota->id) . '" class="btn btn-sm btn-danger">Decline</a>';

                // Menggabungkan kedua tombol dalam satu kolom
                return $btnAccept . ' ' . $btnDecline;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function approve($id)
    {
        try {
            // Ambil data anggota berdasarkan ID
            $anggota = RequestModel::findOrFail($id);

            // Perbarui status menjadi "approved"
            $anggota->status = 'approved';
            $anggota->save();

            // Tambahkan data ke tabel kegiatan_anggota
            DB::table('kegiatan_anggota')->insert([
                'kegiatan_id' => $anggota->kegiatan_id,
                'nip' => $anggota->dosen_nip,
                'id_jabatan' => $anggota->posisi_id,
                'bobot'=> $anggota->bobot,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->back()->with('success', 'Anggota berhasil di-approve.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
public function decline($id)
{
    try {
        // Ambil data anggota berdasarkan ID
        $anggota = RequestModel::findOrFail($id);

        // Perbarui status menjadi "declined"
        $anggota->status = 'rejected';
        $anggota->save();

        return redirect()->back()->with('success', 'Anggota berhasil di-decline.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    
}
