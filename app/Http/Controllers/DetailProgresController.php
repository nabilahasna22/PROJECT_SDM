<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetailProgresController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Detail Progres',
            'list' => ['Home', 'Detail Progres']
        ];

        $page = (object) [
            'title' => 'Daftar detail progres yang terdaftar dalam sistem'
        ];

        $activeMenu = 'detailprogres';

        return view('detailprogres.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }
}
