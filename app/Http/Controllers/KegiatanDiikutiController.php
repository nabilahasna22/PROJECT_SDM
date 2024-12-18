<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KegiatanModel;
use App\Models\KategoriModel;
use App\Models\Wilayah;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class KegiatanDiikutiController extends Controller
{
    /**
     * Menampilkan halaman daftar kegiatan.
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Kegiatan Diikuti',
            'list' => [
                'Home',
                'Kegiatan Diikuti' // Link terakhir dibuat null untuk aktif
            ],
        ];
        $kategori = KategoriModel::all();
        $wilayah = Wilayah::all();
        $activeMenu = 'kegiatan_diikuti'; // Sesuaikan dengan menu aktif

        return view('kegiatan_diikuti.index', compact('breadcrumb', 'activeMenu', 'kategori', 'wilayah'));
    }

    /**
     * Mengambil data kegiatan untuk DataTables.
     */
    public function list(Request $request)
    {
        $query = KegiatanModel::with(['kategori', 'wilayah', 'periode']);

        if ($request->filter_kategori) {
            $query->where('kategori_id', $request->filter_kategori);
        }

        if ($request->filter_wilayah) {
            $query->where('wilayah_id', $request->filter_wilayah);
        }

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('periode', function ($kegiatan) {
                return $kegiatan->periode ? $kegiatan->periode->tahun : '-';
            })
            ->addColumn('surat_tugas', function ($kegiatan) {
                // Pastikan file surat tugas memiliki URL
                if ($kegiatan->surat_tugas) {
                    return asset('uploads/dokumen/' . $kegiatan->surat_tugas);
                }
                return null;
            })
            ->make(true);
    }

    /**
     * Mendownload laporan kegiatan dalam bentuk PDF.
     */
    public function export_pdf(Request $request)
    {
        try {
            // Ambil data kegiatan dengan filter yang diterapkan
            $kategoriId = $request->input('filter_kategori');
            $wilayahId = $request->input('filter_wilayah');

            $kegiatan = KegiatanModel::with('kategori', 'wilayah', 'periode')
                ->when($kategoriId, function ($query, $kategoriId) {
                    return $query->where('kategori_id', $kategoriId);
                })
                ->when($wilayahId, function ($query, $wilayahId) {
                    return $query->where('wilayah_id', $wilayahId);
                })
                ->get();

            foreach ($kegiatan as $item) {
                // Pastikan file surat_tugas memiliki URL atau path absolut
                $item->surat_tugas_url = $item->surat_tugas
                    ? asset('uploads/dokumen/' . $item->surat_tugas)
                    : null; // Jika tidak ada file

                // Konversi tanggal menjadi objek Carbon
                $item->tanggal_mulai = $item->tanggal_mulai ? Carbon::parse($item->tanggal_mulai) : null;
                $item->tanggal_selesai = $item->tanggal_selesai ? Carbon::parse($item->tanggal_selesai) : null;
            }
            // Filter informasi
            $filterKategori = $kategoriId ? KategoriModel::find($kategoriId)->kategori_nama : 'Semua Kategori';
            $filterWilayah = $wilayahId ? Wilayah::find($wilayahId)->nama_wilayah : 'Semua Wilayah';

            // Generate PDF menggunakan view
            $pdf = Pdf::loadView('kegiatan_diikuti.export_pdf', [
                'kegiatan' => $kegiatan,
                'filterKategori' => $filterKategori,
                'filterWilayah' => $filterWilayah,
            ]);

            $pdf->setPaper('a4', 'landscape'); // Atur orientasi PDF

            // Kirim PDF untuk diunduh
            return $pdf->download('laporan_kegiatandiikuti' . date('Ymd_His') . '.pdf');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function download($filename)
    {
        $filePath = public_path('uploads/dokumen/' . $filename);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        abort(404, 'File tidak ditemukan');
    }
}
