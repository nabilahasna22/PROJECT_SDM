<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function get_user()
    {
        $users = DB::table('user as u')
            ->select('u.nip', 'l.level_nama', 'u.username', 'u.nama', 'u.email', 'u.no_telp', 'u.foto', 'u.alamat')
            ->join('level as l', 'u.level_id', '=', 'l.level_id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
}
