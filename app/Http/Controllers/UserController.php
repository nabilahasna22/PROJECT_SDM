<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth; //tambahan
use Yajra\DataTables\DataTables;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; // untuk import
use Illuminate\Support\Facades\Storage; // tambahan

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

        $level = LevelModel::all();
        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
{
    // Mengambil data user dengan relasi level_id
    $user = UserModel::select('nip', 'username', 'nama', 'email', 'password', 'no_telp', 'foto', 'alamat', 'foto', 'level_id')
        ->with('level'); // Mengambil data level terkait

    // Filter berdasarkan level_id jika 
    $level_id = $request->input('filter_level');
    if (!empty($level_id)) {
        $user->where('level_id', $level_id);

    }

    return DataTables::of($user)
        ->addIndexColumn()  // Menambahkan kolom index
        ->addColumn('aksi', function ($user) {
            // Menambahkan tombol aksi untuk Detail, Edit, dan Hapus
            $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->nip . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> '; //menggunakan ajax
            $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->nip . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->nip . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
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
            'nip'      => 'required|string|min:3|unique:user,nip', // Validasi untuk kolom nip
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|unique:user,email', // Validasi email untuk kolom email
            'password' => 'required|min:5',
            'no_telp'  => 'nullable|string|max:20', // Validasi untuk no_telp
            'foto'     => 'nullable|image|max:2048', // Validasi untuk foto (jika ada upload file)
            'alamat'   => 'nullable|string|max:255', // Validasi untuk alamat
            'level_id' => 'required|exists:level,id', // Validasi untuk level_id
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
            'level_id' => 'required|exists:level,id', // Validasi untuk level_id
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

    public function show_ajax(string $id)
    {
        $user = UserModel::with('level')->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Data level tidak ditemukan'
            ]);
        }

        return view('user.show_ajax', ['user' => $user]);
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

    //tambahan ajax
    public function create_ajax() {
        $level = LevelModel::select('level_id', 'level_nama')-> get();

        return view('user.create_ajax')
            ->with('level', $level);
    }

    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|min:8|max:20',
            'level_id' => 'required|exists:level,level_id',
            'username' => 'required|min:3|max:20',
            'nama' => 'required|min:3|max:100',
            'email' => 'required|email',
            'no_telp' => 'nullable|digits_between:10,15',
            'alamat' => 'nullable|min:10|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,ico,bmp|max:2048', // 2MB max
            'password' => 'required|min:5|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Handle file upload
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoName = 'profile_' . time() . '.' . $foto->getClientOriginalExtension();
                $fotoPath = $foto->storeAs('public/profile_pictures', $fotoName);
                $fotoPath = 'profile_pictures/' . $fotoName;
            }

            // Create user
            $user = new UserModel();
            $user->nip = $request->nip;
            $user->level_id = $request->level_id;
            $user->username = $request->username;
            $user->nama = $request->nama;
            $user->email = $request->email;
            $user->no_telp = $request->no_telp;
            $user->alamat = $request->alamat;
            $user->foto = $fotoPath;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'User berhasil ditambahkan',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan user: ' . $e->getMessage()
            ], 500);
        }
    }

public function edit_ajax(string $nip)
{
    $user = UserModel::find($nip);
    
    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Data pengguna tidak ditemukan'
        ], 404);
    }

    $level = LevelModel::select('level_id', 'level_nama')->get();

    return view('user.edit_ajax', [
        'user' => $user, 
        'level' => $level
    ]);
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

    public function confirm_ajax(string $id){
        $user = UserModel::find($id);

        return view('user.confirm_ajax', ['user' => $user]);
    }

    public function delete_ajax(Request $request, $id)
    {
    // Cek apakah request dari ajax
    if ($request->ajax() || $request->wantsJson()) {
        $user = UserModel::find($id);
        
        if ($user) {
            $user->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
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

    // tambahan untuk import
    public function import()
    {
        // Menampilkan form import user
        return view('user.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi file yang diunggah
            $rules = [
                'file_user' => ['required', 'mimes:xlsx', 'max:1024'], // Maksimal ukuran file 1MB
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Ambil file dari request
            $file = $request->file('file_user');
            $reader = IOFactory::createReader('Xlsx'); // Gunakan reader Excel
            $reader->setReadDataOnly(true); // Hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // Load file Excel
            $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet aktif
            $data = $sheet->toArray(null, false, true, true); // Ambil data Excel sebagai array
            $insert = [];

            if (count($data) > 1) {
                foreach ($data as $row => $value) {
                    if ($row > 1) { // Lewati header (baris pertama)
                        $insert[] = [
                            'nip'       => $value['A'], // NIP
                            'level_id'  => $value['B'], // Level ID
                            'username'  => $value['C'], // Username
                            'nama'      => $value['D'], // Nama
                            'password'  => Hash::make($value['E']), // Password (di-hash)
                            'email'     => $value['F'], // Email
                            'no_telp'   => $value['G'], // Nomor Telepon
                            'foto'      => $value['H'] ?? null, // Foto (jika kosong, maka null)
                            'alamat'    => $value['I'],
                            'created_at' => now(), // Waktu pembuatan
                        ];
                    }
                }
            }

            // Masukkan data ke database
            if (count($insert) > 0) {
                UserModel::insertOrIgnore($insert); // Insert data, abaikan jika ada konflik
            }

            return response()->json([
                'status'  => true,
                'message' => 'Data user berhasil diimport'
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }
    }

    public function export_excel()
{
    // Ambil data user yang akan diekspor
    $user = UserModel::select('nip', 'level_id', 'username', 'nama', 'password', 'email', 'no_telp', 'foto', 'alamat')
        ->orderBy('level_id')
        ->with('level')
        ->get();

    // Load library PhpSpreadsheet
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set header kolom
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'NIP');
    $sheet->setCellValue('C1', 'Level ID');
    $sheet->setCellValue('D1', 'Username');
    $sheet->setCellValue('E1', 'Nama');
    $sheet->setCellValue('F1', 'Password');
    $sheet->setCellValue('G1', 'Email');
    $sheet->setCellValue('H1', 'No. Telepon');
    $sheet->setCellValue('I1', 'Foto');
    $sheet->setCellValue('J1', 'Alamat');

    // Format header
    $sheet->getStyle('A1:J1')->getFont()->setBold(true);

    // Isi data
    $no = 1; // Nomor urut
    $baris = 2; // Baris data dimulai dari baris kedua
    foreach ($user as $value) {
        $sheet->setCellValue('A' . $baris, $no);
        $sheet->setCellValue('B' . $baris, $value->nip);
        $sheet->setCellValue('C' . $baris, $value->level_id);
        $sheet->setCellValue('D' . $baris, $value->username);
        $sheet->setCellValue('E' . $baris, $value->nama);
        $sheet->setCellValue('F' . $baris, $value->password); // Hash password tetap ditampilkan
        $sheet->setCellValue('G' . $baris, $value->email);
        $sheet->setCellValue('H' . $baris, $value->no_telp);
        $sheet->setCellValue('I' . $baris, $value->foto ?? '-'); // Jika foto null, tampilkan '-'
        $sheet->setCellValue('J' . $baris, $value->alamat);
        $baris++;
        $no++;
    }

    // Auto-size kolom
    foreach (range('A', 'J') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Set judul sheet
    $sheet->setTitle('Data User');

    // Buat file Excel
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data_User_' . date('Y-m-d_H:i:s') . '.xlsx';

    // Konfigurasi header untuk mengunduh file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit;
}
public function export_pdf()
{
    // Ambil data user yang akan diekspor
    $user = UserModel::select('nip', 'level_id', 'username', 'nama', 'password', 'email', 'no_telp', 'foto', 'alamat')
        ->orderBy('level_id')
        ->with('level')
        ->get();

    // Render tampilan PDF menggunakan view
    $pdf = Pdf::loadView('user.export_pdf', ['user' => $user]);
    $pdf->setPaper('a4', 'portrait'); // Ukuran kertas A4 dengan orientasi portrait
    $pdf->setOption("isRemoteEnabled", true); // Mengaktifkan akses gambar dari URL
    $pdf->render();

    // Stream PDF ke browser
    return $pdf->stream('Data User' . date('Y-m-d_H:i:s') . '.pdf');
}

}
