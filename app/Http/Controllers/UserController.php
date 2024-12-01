<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];
        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];
        $activeMenu = 'user'; // set menu yang sedang aktif
        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
{
    // Mengambil data user dengan relasi level_id
    $users = UserModel::select('nip', 'username', 'nama', 'email', 'password', 'no_telp', 'foto', 'alamat', 'level_id')
        ->with('level'); // Mengambil data level terkait

    // Filter berdasarkan level_id jika ada
    $level_id = $request->input('filter_level');
    if (!empty($level_id)) {
        $users->where('level_id', $level_id);
    }

    return DataTables::of($users)
        ->addIndexColumn()  // Menambahkan kolom index
        ->addColumn('aksi', function ($user) {
            // Menambahkan tombol aksi untuk Detail, Edit, dan Hapus
            $btn = '<a href="' . url('/user/' . $user->nip) . '" class="btn btn-info btn-sm">Detail</a> '; // Ganti 'level_id' menjadi 'nip'
            $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->nip . '/edit').'\')" class="btn btn-warning btn-sm">Edit</button>'; // Ganti 'level_id' menjadi 'nip'
            $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->nip . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> '; // Ganti 'level_id' menjadi 'nip'
            return $btn;
        })
        ->rawColumns(['aksi']) // Memberitahu DataTables bahwa kolom aksi berisi HTML
        ->make(true); // Mengembalikan hasil dalam format JSON untuk DataTables
}

    // Menampilkan halaman form tambah user
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];
        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif
        $Level = LevelModel::all();
        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'Level' => $Level, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan data user baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nip'      => 'required|string|min:3|unique:users,nip', // Validasi untuk kolom nip
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email', // Validasi email untuk kolom email
            'password' => 'required|min:5',
            'no_telp'  => 'nullable|string|max:20', // Validasi untuk no_telp
            'foto'     => 'nullable|image|max:2048', // Validasi untuk foto (jika ada upload file)
            'alamat'   => 'nullable|string|max:255', // Validasi untuk alamat
            'level_id' => 'required|exists:levels,id', // Validasi untuk level_id
        ]);
    
        // Proses penyimpanan data ke UserModel
        $user = UserModel::create([
            'nip'      => $request->nip,      // Menyimpan nilai nip
            'username' => $request->username, // Menyimpan username (jika ada di form)
            'nama'     => $request->nama,     // Menyimpan nama
            'email'    => $request->email,    // Menyimpan email
            'password' => bcrypt($request->password), // Enkripsi password
            'no_telp'  => $request->no_telp, // Menyimpan no_telp
            'foto'     => $this->uploadFoto($request), // Upload foto (jika ada)
            'alamat'   => $request->alamat,  // Menyimpan alamat
            'level_id' => 'required|exists:levels,id', // Validasi untuk level_id
        ]);
    
        // Redirect dengan pesan sukses
        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }
    
    // Fungsi untuk mengupload foto jika ada
    protected function uploadFoto(Request $request)
    {
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $path = $file->store('public/fotos'); // Menyimpan foto di folder public/fotos
            return basename($path); // Mengembalikan nama file yang disimpan
        }
        return null; // Jika tidak ada foto yang diupload
    }
    
    // Menampilkan detail user
    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);
        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail user'
        ];
        $activeMenu = 'user';
        $Level = LevelModel::all();
        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'Level' => $Level, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan halaman untuk edit user
    public function edit(string $id)
    {
        $user = UserModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit user'
        ];
        $Level = LevelModel::all();
        $activeMenu = 'user'; // set menu yang sedang aktif
        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'Level' => $Level, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan perubahan data user
    public function update(Request $request, string $nip)
{
    // Validasi input
    $request->validate([
        'nip'      => 'required|string|min:3|unique:user,nip,'.$nip.',nip', // Perbaiki validasi untuk nip
        'nama'     => 'required|string|max:100',
        'email'    => 'required|email|unique:user,email,'.$nip.',nip', // Perbaiki validasi untuk email
        'password' => 'nullable|min:5', // Password hanya diperlukan jika diubah
        'no_telp'  => 'nullable|string|max:20', // Validasi untuk no_telp
        'foto'     => 'nullable|image|max:2048', // Validasi foto (jika ada upload file)
        'alamat'   => 'nullable|string|max:255', // Validasi untuk alamat
        'level_id' => 'required|exists:level,level_id', // Validasi untuk level_id
    ]);

    // Temukan user berdasarkan nip
    $user = UserModel::findOrFail($nip);

    // Update data user
    $user->update([
        'nip'      => $request->nip,      // Menyimpan nilai nip
        'username' => $request->username, // Menyimpan username (jika ada di form)
        'nama'     => $request->nama,     // Menyimpan nama
        'email'    => $request->email,    // Menyimpan email
        'password' => $request->password ? bcrypt($request->password) : $user->password, // Update password jika ada
        'no_telp'  => $request->no_telp,  // Menyimpan no_telp
        'foto'     => $this->uploadFoto($request, $user), // Update foto jika ada
        'alamat'   => $request->alamat,   // Menyimpan alamat
        'level_id' => $request->level_id, // Menyimpan level_id yang dipilih
    ]);

    // Redirect dengan pesan sukses
    return redirect('/user')->with('success', 'Data user berhasil diubah');
}


    // Menghapus data user
    public function destroy(string $id)
    {
        $check = UserModel::find($id);
        if (!$check) {
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }

        try {
            UserModel::destroy($id);
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
