<?php
namespace App\Http\Controllers;

use App\Models\AgendaProgresModel;
use App\Models\KegiatanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
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
        $user = Auth::user();

        $query = AgendaProgresModel::with(['kegiatan', 'user'])
            ->where('nip', $user->nip)
            ->select('agenda_progres.*');

        // Filter by kegiatan
        // if ($request->has('filter_kegiatan') && !empty($request->filter_kegiatan)) {
        //     $query->where('kegiatan_id', $request->filter_kegiatan);
        // }

        // Filter by progress
        if ($request->has('filter_progress') && !empty($request->filter_progress)) {
            $query->where('progress', $request->filter_progress);
        }        

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                $btn = '
                    <div class="btn-group">
                        <button onclick="modalAction(\''.url('/agenda_progres/edit_ajax/'.$row->id_progres).'\')" class="btn btn-sm btn-warning">
                            <i class="fa fa-edit"></i>
                        </button>
                    </div>
                ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
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
            $agendaProgress = new AgendaProgresModel();
            $agendaProgress->kegiatan_id = $request->kegiatan_id;
            $agendaProgress->nip = $user->nip;
            $agendaProgress->progress = $request->progress;

            // Handle file upload
            if ($request->hasFile('file_dokumen')) {
                $file = $request->file('file_dokumen');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/dokumen'), $filename);
                $agendaProgress->file_dokumen = $filename;
            }

            $agendaProgress->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function edit_ajax($id)
    // {
    //     $user = Auth::user();
        
    //     // Ensure the agenda progress belongs to the current user
    //     $data = AgendaProgresModel::where('id_progres', $id)
    //         ->where('nip', $user->nip)
    //         ->firstOrFail();
        
    //     // Get kegiatan only for the current user
    //     $kegiatan = KegiatanModel::whereHas('agenda_progres', function($query) use ($user) {
    //         $query->where('nip', $user->nip);
    //     })->get();
        
    //     $user = UserModel::where('nip', $user->nip)->get();
    //     return view('agenda_progres.edit_ajax', compact('data', 'kegiatan', 'users'));
    // }

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
    
    $user = UserModel::where('nip', $user->nip)->first(); // Changed to first() instead of get()
    
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