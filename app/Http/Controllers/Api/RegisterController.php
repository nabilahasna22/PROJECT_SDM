<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NIP'       => 'required|unique:user',  // nip harus unik
            'nama'      => 'required',
            'email'     => 'required|email|unique:user',  // email harus unik dan format email valid
            'password'  => 'required|min:5|confirmed', // role harus 1, 2, atau 3
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = UserModel::create([
            'NIP'       => $request->NIP,
            'nama'      => $request->nama,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
        ]);

        if ($user) {
            return response()->json([
                'success'   => true,
                'user'      => $user,
            ], 201);
        }

        return response()->json([
            'success'   => false,
        ], 409);
    }
}
