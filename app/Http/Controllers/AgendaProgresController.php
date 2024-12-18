<?php
namespace App\Http\Controllers;

use App\Models\AgendaProgresModel;
use App\Models\KegiatanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AgendaProgresController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $breadcrumb = (object) [
            'title' => 'Daftar Agenda Progress',
            'list' => ['Home', 'Agenda Progress']
        ];
        
        $page = (object) [
            'title' => 'Daftar agenda progress yang terdaftar dalam sistem'
        ];
        
        // Get kegiatan only for the current user
        $kegiatan = KegiatanModel::whereHas('agenda_progres', function($query) use ($user) {
            $query->where('nip', $user->nip);
        })->get();

        $activeMenu = 'agenda_progres';
        
        return view('agenda_progres.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kegiatan' => $kegiatan
        ]);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user(); // Mendapatkan user yang sedang login

            // Query data dari tabel AgendaProgresModel dengan relasi yang diperlukan
            $data = AgendaProgresModel::with(['kegiatan', 'user'])
                ->where('nip', $user->nip) // Filter berdasarkan NIP user yang login
                ->select('agenda_progres.*');

            // Filter berdasarkan progress
            if ($request->has('filter_progress') && !empty($request->filter_progress)) {
                $data->where('progress', $request->filter_progress);
            }

            return DataTables::of($data)
                ->addIndexColumn() // Menambahkan kolom index
                ->addColumn('aksi', function ($row) {
                    $btn = '
                        <a href="' . url('/agenda_progres/masuk/' . $row->id_progres) . '" class="edit btn btn-sm btn-primary">
                            Tambah Progres
                        </a>
                    ';
                    return $btn;
                })
                ->rawColumns(['aksi']) // Mengizinkan HTML pada kolom aksi
                ->make(true);
        }
    }

    public function masuk($id)
    {
        $breadcrumb = (object)[
            'title'=>'Tambah Progres Kegiatan',
            'list'=>['Home','Tambah Progres Kegiatan']
        ];

        $activeMenu = 'agenda_progres';
        $agendaProgres = AgendaProgresModel::with(['kegiatan', 'user'])->find($id);
        return view('agenda_progres.masuk', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'agendaProgres' => $agendaProgres
        ]);
    }

    public function listprogres(Request $request, $id)
    {
        $progres = AgendaProgresModel::with('kegiatan','user')->where('kegiatan_id',$id)->get();

        return DataTables::of($progres)
            ->addIndexColumn()
            ->addColumn('aksi', function ($progres) { 
                $btn  = '<button onclick="modalAction(\'' . url('/agenda_progres/' . $progres->id . '/confirm') . '\')" class="btn btn-info btn-sm">Edit</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) 
            ->make(true);
    }

    public function detail($id)
    {
        $breadcrumb = (object)[
            'title' => 'Detail Agenda Progres',
            'list' => ['Home', 'Agenda Progres', 'Detail']
        ];

        $activeMenu = 'agenda_progres';
        $agendaProgres = AgendaProgresModel::with(['kegiatan', 'user'])->find($id);
        return view('agenda_progres.detail', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'agendaProgres' => $agendaProgres
        ]);
    }

    public function create_ajax()
    {
        $user = Auth::user();
        
        // Get kegiatan only for the current user
        $kegiatan = KegiatanModel::whereHas('agenda_progres', function($query) use ($user) {
            $query->where('nip', $user->nip);
        })->get();
        
        $users = UserModel::where('nip', $user->nip)->get();
        return view('agenda_progres.create_ajax', compact('kegiatan', 'users'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
    
        $validator = Validator::make($request->all(), [
            'kegiatan_id' => [
                'required', 
                'exists:kegiatan,kegiatan_id',
                function($attribute, $value, $fail) use ($user) {
                    // Modifikasi validasi untuk memastikan kegiatan bisa diakses
                    $exists = KegiatanModel::where('kegiatan_id', $value)
                        ->exists();
                    
                    if (!$exists) {
                        $fail('Kegiatan tidak ditemukan.');
                    }
                }
            ],
            'progress' => 'required|in:on_progress,completed,not_started',
            'file_dokumen' => 'nullable|file|max:5120', // 5MB max
            'file_deskripsi' => 'nullable|string|max:255'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }
    
        try {
            $agendaProgress = new AgendaProgresModel();
            $agendaProgress->kegiatan_id = $request->kegiatan_id;
            $agendaProgress->nip = $user->nip;
            $agendaProgress->progress = $request->progress;
            
            // Tambahkan deskripsi jika ada
            if ($request->filled('file_deskripsi')) {
                $agendaProgress->file_deskripsi = $request->file_deskripsi;
            }
    
            // Handle file upload
            if ($request->hasFile('file_dokumen')) {
                $file = $request->file('file_dokumen');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/dokumen'), $filename);
                $agendaProgress->file_dokumen = $filename;
            }
    
            // Debug: Cetak data sebelum save
            Log::info('Data AgendaProgres:', [
                'kegiatan_id' => $agendaProgress->kegiatan_id,
                'nip' => $agendaProgress->nip,
                'progress' => $agendaProgress->progress,
                'file_dokumen' => $agendaProgress->file_dokumen
            ]);
    
            $saved = $agendaProgress->save();
    
            // Tambahkan debug
            if (!$saved) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan data'
                ], 500);
            }
    
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => $agendaProgress // Kirim data kembali untuk konfirmasi
            ]);
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error menyimpan AgendaProgres: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
    
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
    

    public function edit_ajax($id)
    {
        $user = Auth::user();
        
        // Ensure the agenda progress belongs to the current user
        $agenda_progres = AgendaProgresModel::where('id_progres', $id)
            ->where('nip', $user->nip)
            ->firstOrFail();
        
        // Get kegiatan only for the current user
        $kegiatan = KegiatanModel::whereHas('agenda_progres', function($query) use ($user) {
            $query->where('nip', $user->nip);
        })->get();
        
        $user = UserModel::where('nip', $user->nip)->first();
        
        return view('agenda_progres.edit_ajax', compact('agenda_progres', 'kegiatan', 'user'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'kegiatan_id' => [
                'required', 
                'exists:kegiatan,kegiatan_id',
                function($attribute, $value, $fail) use ($user) {
                    // Ensure the selected kegiatan belongs to the user
                    $exists = AgendaProgresModel::where('nip', $user->nip)
                        ->where('kegiatan_id', $value)
                        ->exists();
                    
                    if (!$exists) {
                        $fail('Kegiatan tidak valid untuk pengguna ini.');
                    }
                }
            ],
            'progress' => 'required|in:on_progress,completed,not_started',
            'file_dokumen' => 'nullable|file|max:5120', // 5MB max
            'file_deskripsi' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $agendaProgress = AgendaProgresModel::where('id_progres', $id)
                ->where('nip', $user->nip)
                ->firstOrFail();

            $agendaProgress->kegiatan_id = $request->kegiatan_id;
            $agendaProgress->progress = $request->progress;

            // Handle file upload
            if ($request->hasFile('file_dokumen')) {
                // Delete old file if exists
                if ($agendaProgress->file_dokumen) {
                    $oldFilePath = public_path('uploads/dokumen/' . $agendaProgress->file_dokumen);
                    if (File::exists($oldFilePath)) {
                        File::delete($oldFilePath);
                    }
                }

                $file = $request->file('file_dokumen');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/dokumen'), $filename);
                $agendaProgress->file_dokumen = $filename;
            }

            // Update file_deskripsi
            if ($request->filled('file_deskripsi')) {
                $agendaProgress->file_deskripsi = $request->file_deskripsi;
            }

            $agendaProgress->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        $user = Auth::user();

        try {
            $agendaProgress = AgendaProgresModel::where('id_progres', $id)
                ->where('nip', $user->nip)
                ->firstOrFail();

            // Delete file if exists
            if ($agendaProgress->file_dokumen) {
                $filePath = public_path('uploads/dokumen/' . $agendaProgress->file_dokumen);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }

            $agendaProgress->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download($filename)
    {
        $user = Auth::user();

        // Ensure the file belongs to the current user
        $fileExists = AgendaProgresModel::where('nip', $user->nip)
            ->where('file_dokumen', $filename)
            ->exists();

        if (!$fileExists) {
            abort(403, 'Unauthorized access');
        }

        $filePath = public_path('uploads/dokumen/' . $filename);
        
        if (File::exists($filePath)) {
            return response()->download($filePath);
        }

        abort(404, 'File not found');
    }
}