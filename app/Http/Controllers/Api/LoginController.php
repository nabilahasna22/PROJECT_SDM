<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'password' => 'required'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Ambil kredensial
        $credentials = $request->only('nip', 'password');

        // Cek autentikasi
        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'nip atau Password Anda salah'
            ], 401);
        }

        // Ambil data pengguna
        $user = auth()->guard('api')->user();
        $level_id = $user->level_id; // Mengambil role_id dari UserModel
        $nip = $user->nip; // Mengambil user_id dari UserModel

        // Respons jika login berhasil
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $nip, // Menampilkan user_id
                'username' => $user->username,
                'level_id' => $level_id
            ],
            'token' => $token
        ], 200);
    }
}
