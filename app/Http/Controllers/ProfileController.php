<?php
namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // tambahan

class ProfileController extends Controller
{
    public function index()
    {
        $id = session('nip');
        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => ['Home', 'Profile']
        ];
        $page = (object) [
            'title' => 'Profile Anda'
        ];
        $activeMenu = 'profile';
        $user = UserModel::with('level')->find($id);
        $level = LevelModel::all();

        return view('profile.index', compact('breadcrumb', 'page', 'activeMenu', 'user', 'level'));
    }

    public function edit_ajax($nip)
    {
        $user = UserModel::find($nip);
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('profile.edit_ajax', compact('user', 'level'));
    }

    public function update_ajax(Request $request, string $nip)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'nip'        => 'required|string|unique:user,nip,'.$nip.',nip',
        'username'   => 'required|string|max:50|unique:user,username,'.$nip.',nip',
        'nama'       => 'required|string|max:100',
        'email'      => 'required|email|unique:user,email,'.$nip.',nip',
        'level_id'   => 'required|exists:level,level_id',
        'no_telp'    => 'nullable|string|max:20',
        'alamat'     => 'nullable|string|max:255',
        'password'   => 'nullable|min:5|max:255',
        'foto'       => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif'
    ]);

    // Jika validasi gagal
    if ($validator->fails()) {
        return response()->json([
            'status'   => false,
            'message'  => 'Validasi gagal',
            'errors'   => $validator->errors()
        ], 422);
    }

    // Cari user
    $user = UserModel::find($nip);
    if (!$user) {
        return response()->json([
            'status'  => false,
            'message' => 'Pengguna tidak ditemukan'
        ], 404);
    }

    // Persiapkan data update
    $updateData = [
        'nip'        => $request->nip,
        'username'   => $request->username,
        'nama'       => $request->nama,
        'email'      => $request->email,
        'level_id'   => $request->level_id,
        'no_telp'    => $request->no_telp,
        'alamat'     => $request->alamat
    ];

    // Update password jika diisi
    if ($request->filled('password')) {
        $updateData['password'] = bcrypt($request->password);
    }

    // Proses upload foto
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $fileName = 'profile_' . $nip . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/profile_pictures', $fileName);
        $updateData['foto'] = 'profile_pictures/' . $fileName;

        // Hapus foto lama jika ada
        if ($user->foto) {
            Storage::delete('public/' . $user->foto);
        }
    }

    // Update user
    try {
        $user->update($updateData);
        
        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil diupdate'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Gagal mengupdate data: ' . $e->getMessage()
        ], 500);
    }
}

    public function edit_foto(string $nip)
    {
        $user = UserModel::find($nip);
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('profile.edit_foto', ['user' => $user, 'level'=>$level]);
    }

    public function update_foto(Request $request, $nip)
{
    // Cek apakah request dari AJAX
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'foto' => 'required|mimes:jpeg,png,jpg|max:4096'
        ];

        // Validasi input
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        // Cek apakah pengguna ada
        $check = UserModel::find($nip);
        if ($check) {
            // Jika ada file foto yang diunggah
            if ($request->hasFile('foto')) {
                // Nama file lama untuk dihapus
                $oldFilePath = $check->foto; // Ambil path foto lama dari database

                // Hapus foto lama jika ada
                if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }

                // Proses penyimpanan foto baru
                $file = $request->file('foto');
                $fileName = 'profile_' . $nip . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/profile_pictures', $fileName);

                // Update foto di database dengan path relatif
                $publicPath = 'profile_pictures/' . $fileName;

                // Update foto di database
                $check->update([
                    'foto' => $publicPath
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate',
                'new_photo_url' => $publicPath ?? $check->foto // Tambahkan URL foto baru untuk update tampilan
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    return redirect('/');
}

}
