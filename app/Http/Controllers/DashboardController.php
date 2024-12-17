<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $activeMenu = 'dashboard';
        $totalDosen = DB::table('user')->where('level_id', 2)->count();
        $terprogram = DB::table('kegiatan')->where('kategori_id', 1)->count();
        $nonProgram = DB::table('kegiatan')->where('kategori_id', 2)->count();
        $nonJti = DB::table('kegiatan')->where('kategori_id', 3)->count();


        return view('dashboard.admin', compact('totalDosen', 'terprogram', 'nonProgram', 'nonJti'));
    }

    public function dosenDashboard()
    {
        $activeMenu = 'dashboard';
        $nip = auth()->user()->nip;
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

        return view('dashboard.dosen', compact('terprogramDosen', 'nonProgramDosen', 'nonJtiDosen'));
    }

    public function pimpinanDashboard()
    {
        $activeMenu = 'dashboard';
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

        return view('dashboard.pimpinan', compact('dosenikutkegiatan', 'progreskegiatanterlaksana', 'progreskegiatanberjalan'));
    }
}