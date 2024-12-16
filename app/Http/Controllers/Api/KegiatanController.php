<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KegiatanController extends Controller
{

    public function get_kegiatan()
    {
        $kegiatan = DB::table('kegiatan as k')
            ->select(
                'k.kegiatan_id',
                'kt.kategori_nama',
                'w.nama_wilayah',
                'p.tahun',
                'k.kegiatan_nama',
                'k.deskripsi',
                'k.tanggal_mulai',
                'k.tanggal_selesai',
                'k.status'
            )
            ->join('kategori as kt', 'k.kategori_id', '=', 'kt.kategori_id')
            ->join('wilayah_kegiatan as w', 'k.id_wilayah', '=', 'w.id_wilayah')
            ->join('periode_kegiatan as p', 'k.periode_id', '=', 'p.periode_id')
            ->get();

        $dosenPicMap = DB::table('kegiatan_anggota as ka')
            ->join('jabatan as j', 'ka.id_jabatan', '=', 'j.id_jabatan')
            ->where('j.id_jabatan', 1)
            ->select('ka.kegiatan_id', 'ka.nip')
            ->get()
            ->groupBy('kegiatan_id')
            ->map(function ($items) {
                return $items->pluck('nip')->first();
            });

        $dosenSkrMap = DB::table('kegiatan_anggota as ka')
            ->join('jabatan as j', 'ka.id_jabatan', '=', 'j.id_jabatan')
            ->where('j.id_jabatan', 2)
            ->select('ka.kegiatan_id', 'ka.nip')
            ->get()
            ->groupBy('kegiatan_id')
            ->map(function ($items) {
                return $items->pluck('nip')->first();
            });

        $dosenbdrMap = DB::table('kegiatan_anggota as ka')
            ->join('jabatan as j', 'ka.id_jabatan', '=', 'j.id_jabatan')
            ->where('j.id_jabatan', 3)
            ->select('ka.kegiatan_id', 'ka.nip')
            ->get()
            ->groupBy('kegiatan_id')
            ->map(function ($items) {
                return $items->pluck('nip')->first();
            });

        $anggotaMap = DB::table('kegiatan_anggota')
            ->get()
            ->groupBy('kegiatan_id')
            ->map(function ($items) {
                return $items->pluck('nip')->toArray();
            });

        $kegiatan = $kegiatan->map(function ($item) use ($dosenPicMap, $dosenSkrMap, $dosenbdrMap, $anggotaMap) {
            $item->dosen_pic = $dosenPicMap->get($item->kegiatan_id);
            $item->dosen_skr = $dosenSkrMap->get($item->kegiatan_id);
            $item->dosen_bdr = $dosenbdrMap->get($item->kegiatan_id);
            $item->user = $anggotaMap->get($item->kegiatan_id);

            return $item;
        });

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data kegiatan',
            'data' => $kegiatan
        ], 200);
    }


    public function add_pending_approval(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id' => 'required|integer',
            'posisi_id' => 'required|integer',
            'dosen_nip' => 'required|integer',
            'bobot' => 'required|integer',
        ]);

        $inserted = DB::table('pending_approval')->insert([
            'kegiatan_id' => $validated['kegiatan_id'],
            'posisi_id' => $validated['posisi_id'],
            'dosen_nip' => $validated['dosen_nip'],
            'bobot' => $validated['bobot'],
            'status' => 'pending',
            'created_at' => now(),
        ]);

        if ($inserted) {
            return response()->json([
                'success' => true,
                'message' => 'Permintaan berhasil disimpan untuk konfirmasi.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan permintaan.',
        ], 500);
    }

    public function get_pending_approvals()
    {
        $pendingApprovals = DB::table('pending_approval as pa')
            ->join('kegiatan as k', 'pa.kegiatan_id', '=', 'k.kegiatan_id')
            ->join('user as d', 'pa.dosen_nip', '=', 'd.nip')
            ->join('jabatan as p', 'pa.posisi_id', '=', 'p.id_jabatan')
            ->select(
                'pa.id',
                'pa.kegiatan_id',
                'k.kegiatan_nama',
                'd.nama as dosen_nama',
                'p.nama_jabatan as posisi_nama',
                'pa.bobot',
                'pa.status',
                'pa.created_at'
            )
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data pending approval',
            'data' => $pendingApprovals
        ], 200);
    }

    public function process_pending_approval(Request $request)
    {
        $validated = $request->validate([
            'approval_id' => 'required|integer',
            'action' => 'required|string|in:approve,reject',
        ]);

        $approval = DB::table('pending_approval')->where('id', $validated['approval_id'])->first();

        if (!$approval) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan tidak ditemukan.',
            ], 404);
        }

        if ($validated['action'] === 'approve') {
            try {
                DB::transaction(function () use ($approval) {
                    // Tambahkan data ke tabel kegiatan_anggota
                    DB::table('kegiatan_anggota')->insert([
                        'kegiatan_id' => $approval->kegiatan_id,
                        'nip' => $approval->dosen_nip,
                        'id_jabatan' => $approval->posisi_id,
                        'bobot' => $approval->bobot,
                    ]);

                    // Perbarui status di tabel pending_approval menjadi "diterima"
                    DB::table('pending_approval')->where('id', $approval->id)->update(['status' => 'approved']);
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Permintaan berhasil disetujui.',
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memproses permintaan: ' . $e->getMessage(),
                ], 500);
            }
        } elseif ($validated['action'] === 'reject') {
            // Tolak permintaan
            DB::table('pending_approval')->where('id', $approval->id)->update(['status' => 'rejected']);

            return response()->json([
                'success' => true,
                'message' => 'Permintaan berhasil ditolak.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Aksi tidak valid.',
        ], 400);
    }
}
