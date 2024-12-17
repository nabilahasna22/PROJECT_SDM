<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\KategoriController;

class WelcomeController extends Controller
{
    public function index()
    {
        // Data breadcrumb untuk navigasi
        $breadcrumb = (object)[
            'title' => 'Selamat Datang',
            'list'  => ['Home', 'Welcome'],
        ];

        // Menu aktif
        $activeMenu = 'dashboard';

        // Ambil data dinamis dari database
        $totalDosen = DB::table('user')->where('level_id', 2)->count();
        $terprogram = DB::table('kegiatan')->where('kategori_id', 1)->count();
        $nonProgram = DB::table('kegiatan')->where('kategori_id', 2)->count();
        $nonJti = DB::table('kegiatan')->where('kategori_id', 3)->count();

        // Data kegiatan yang diikuti dosen (hanya untuk level dosen)
        $kegiatanDosen = [];
        $terprogramDosen = 0;
        $nonProgramDosen = 0;
        $nonJtiDosen = 0;

        if (auth()->check() && auth()->user()->level_id == 2) {
            $nip = auth()->user()->nip;

            // Ambil data kegiatan berdasarkan kategori untuk dosen
            $kegiatanDosen = DB::table('kegiatan')
                ->join('kegiatan_anggota', 'kegiatan.kegiatan_id', '=', 'kegiatan_anggota.kegiatan_id')
                ->where('kegiatan_anggota.nip', $nip)
                ->get(['kegiatan.kegiatan_nama', 'kegiatan.tanggal_mulai']);

            $terprogramDosen = DB::table('kegiatan')
                ->join('kegiatan_anggota', 'kegiatan.kegiatan_id', '=', 'kegiatan_anggota.kegiatan_id')
                ->where('kegiatan_anggota.nip', $nip)
                ->where('kegiatan.kategori_id', 1)
                ->count();

            $nonProgramDosen = DB::table('kegiatan')
                ->join('kegiatan_anggota', 'kegiatan.kegiatan_id', '=', 'kegiatan_anggota.kegiatan_id')
                ->where('kegiatan_anggota.nip', $nip)
                ->where('kegiatan.kategori_id', 2)
                ->count();

            $nonJtiDosen = DB::table('kegiatan')
                ->join('kegiatan_anggota', 'kegiatan.kegiatan_id', '=', 'kegiatan_anggota.kegiatan_id')
                ->where('kegiatan_anggota.nip', $nip)
                ->where('kegiatan.kategori_id', 3)
                ->count();
        }

        // Data progres kegiatan berdasarkan status
        $progreskegiatanterlaksana = DB::table('kegiatan')
            ->where('status', 'terlaksana') // Ambil hanya status terlaksana
            ->count(); // Menghitung jumlah data

        $progreskegiatanberjalan = DB::table('kegiatan')
            ->where('status', 'on progres') // Ambil hanya status 'on progress'
            ->count(); // Menghitung jumlah data
        
        $dosenikutkegiatan = DB::table('kegiatan_anggota')
            ->select('kegiatan_id', DB::raw('COUNT(DISTINCT nip) as jumlah_dosen'))
            ->groupBy('kegiatan_id')
            ->count();

        
        // Ambil data kategori (dari KategoriController)
        $kategoriController = new KategoriController();
        $kategori = $kategoriController->getKategori();

        // Kirim data ke view
        return view('welcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'totalDosen' => $totalDosen,
            'terprogram' => $terprogram,
            'nonProgram' => $nonProgram,
            'nonJti' => $nonJti,
            'kegiatanDosen' => $kegiatanDosen,
            'kategoriData' => $kategori,
            'terprogramDosen' => $terprogramDosen,
            'nonProgramDosen' => $nonProgramDosen,
            'nonJtiDosen' => $nonJtiDosen,
            'progreskegiatanterlaksana' => $progreskegiatanterlaksana,
            'dosenikutkegiatan' => $dosenikutkegiatan,
            'progreskegiatanberjalan' => $progreskegiatanberjalan,
        ]);
    }

    // API untuk data dashboard
    public function getDashboardData()
    {
        // Ambil data yang diperlukan untuk dashboard
        $totalDosen = DB::table('user')->where('level_id', 2)->count();
        $terprogram = DB::table('kegiatan')->where('kategori_id', 1)->count();
        $nonProgram = DB::table('kegiatan')->where('kategori_id', 2)->count();
        $nonJti = DB::table('kegiatan')->where('kategori_id', 3)->count();

        // Kirimkan data dalam format JSON
        return response()->json([
            'total_dosen' => $totalDosen,
            'terprogram' => $terprogram,
            'non_program' => $nonProgram,
            'non_jti' => $nonJti,
        ]);
    }

    // Fungsi status untuk dashboard utama
    public function status()
    {
        $breadcrumb = (object)[
            'title' => 'Dashboard',
            'list'  => ['Home', 'Dashboard'],
        ];

        $activeMenu = 'dashboard';

        return view('dashboard', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
        ]);
    }
}
