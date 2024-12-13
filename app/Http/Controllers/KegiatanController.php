<?php
namespace App\Http\Controllers;

use App\Models\KegiatanModel;
use App\Models\KategoriModel;
use App\Models\PeriodeModel;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class KegiatanController extends Controller
{
    public function index()
    {
    $activeMenu = 'kegiatan';
    $breadcrumb = (object)[
        'title' => 'Data Kegiatan',
        'list'  => ['Home', 'Kegiatan']
    ];

    // Mengambil data kategori dan wilayah
    $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
    $wilayah = Wilayah::select('id_wilayah', 'nama_wilayah')->get();
    $periode = PeriodeModel::select('periode_id', 'tahun')->get();

    // Mengirim semua data ke view
    return view('kegiatan.index', [
        'activeMenu'  => $activeMenu,
        'breadcrumb'  => $breadcrumb,
        'kategori'    => $kategori,
        'wilayah'     => $wilayah,
        'periode'     => $periode
    ]);
    }


    public function list(Request $request)
    {
        $kegiatan = KegiatanModel::select('kategori_id', 'kegiatan_id', 'kegiatan_nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'status', 'id_wilayah', 'periode_id')
            ->with(['kategori', 'wilayah', 'periode']); 
    
        $kategori_id = $request->input('filter_kategori');
        if (!empty($kategori_id)) {
            $kegiatan->where('kategori_id', $kategori_id);
        }
        $id_wilayah = $request->input('filter_wilayah');
        if (!empty($id_wilayah)) {
            $kegiatan->where('id_wilayah', $id_wilayah);
        }
        $periode_id = $request->input('filter_periode');
        if (!empty($periode_id)) {
            $kegiatan->where('periode_id', $periode_id);
        }
    
        return DataTables::of($kegiatan)
        ->addIndexColumn()
        ->addColumn('surat_tugas', function ($kegiatan) {
            if ($kegiatan->surat_tugas) {
                return '
                    <a href="' . route('download.surat_tugas', $kegiatan->kegiatan_id) . '" class="btn btn-primary btn-sm" title="Download Surat Tugas">
                        <i class="fas fa-download"></i>
                    </a>
                    <button onclick="hapusSuratTugas(' . $kegiatan->kegiatan_id . ')" class="btn btn-danger btn-sm" title="Hapus Surat Tugas">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            } else {
                return '<a href="' . route('kegiatan.upload_surat', $kegiatan->kegiatan_id) . '" class="btn btn-success btn-sm" title="Upload Surat Tugas">
                    <i class="fas fa-upload"></i> Upload
                </a>';
            }
        })
        ->addColumn('aksi', function ($kegiatan) {
            $btn = '<button onclick="modalAction(\''.url('/kegiatan/'. $kegiatan->kegiatan_id . '/show_ajax').'\')" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-bars"></i></button>';
            $btn .= '<button onclick="modalAction(\''.url('/kegiatan/'. $kegiatan->kegiatan_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></button>';
            $btn .= '<button onclick="modalAction(\''.url('/kegiatan/' . $kegiatan->kegiatan_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>';
            return $btn;
        })
        ->rawColumns(['surat_tugas', 'aksi'])
        ->make(true);
}

    public function create_ajax()
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
        $wilayah = Wilayah::select('id_wilayah', 'nama_wilayah')->get();
        $periode = PeriodeModel::select('periode_id', 'tahun')->get();
        return view('kegiatan.create_ajax', [
            'kategori' => $kategori,
            'wilayah' => $wilayah,
            'periode' => $periode
        ]);
    }

    public function edit_ajax(string $id)
    {
        $kegiatan = KegiatanModel::find($id); // Cari data kegiatan berdasarkan ID
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get(); // Ambil data kategori
        $wilayah = Wilayah::select('id_wilayah', 'nama_wilayah')->get(); // Ambil data wilayah
        $periode = PeriodeModel::select('periode_id', 'tahun')->get();
    
        return view('kegiatan.edit_ajax', [
            'kegiatan' => $kegiatan,
            'kategori' => $kategori,
            'wilayah' => $wilayah,
            'periode' => $periode
        ]);
    }
    
    public function update_ajax(Request $request, $id)
{
    // Validasi request
    $rules = [
        'kategori_id'      => 'sometimes|exists:kategori,kategori_id',
        'id_wilayah'       => 'sometimes|exists:wilayah_kegiatan,id_wilayah',
        'kegiatan_nama'    => 'sometimes|string|min:3|max:100',
        'deskripsi'        => 'nullable|string|max:500',
        'tanggal_mulai'    => 'sometimes|date',
        'tanggal_selesai'  => 'sometimes|date|after_or_equal:tanggal_mulai',
        'status'           => 'sometimes|string|in:on progres,terlaksana',        
        'periode_id'       => 'required|exists:periode_kegiatan,periode_id',
        'surat_tugas'      => 'nullable|file|mimes:pdf,doc,docx|max:2048', // contoh batasan file
    ];
    
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validasi gagal.',
            'errors' => $validator->errors()
        ], 422);
    }
    
    $kegiatan = KegiatanModel::find($id);
    if (!$kegiatan) {
        return response()->json([
            'status' => false,
            'message' => 'Data kegiatan tidak ditemukan'
        ], 404);
    }
    
    // Proses upload surat tugas
    if ($request->hasFile('surat_tugas')) {
        $file = $request->file('surat_tugas');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('surat_tugas', $filename, 'public');
        
        // Hapus file lama jika ada
        if ($kegiatan->surat_tugas && Storage::disk('public')->exists($kegiatan->surat_tugas)) {
            Storage::disk('public')->delete($kegiatan->surat_tugas);
        }
        
        $kegiatan->surat_tugas = $path;
    }
    
    $kegiatan->update([
        'kategori_id'      => $request->kategori_id ?? $kegiatan->kategori_id,
        'id_wilayah'       => $request->id_wilayah ?? $kegiatan->id_wilayah,
        'kegiatan_nama'    => $request->kegiatan_nama ?? $kegiatan->kegiatan_nama,
        'deskripsi'        => $request->deskripsi ?? $kegiatan->deskripsi,
        'tanggal_mulai'    => $request->tanggal_mulai ?? $kegiatan->tanggal_mulai,
        'tanggal_selesai'  => $request->tanggal_selesai ?? $kegiatan->tanggal_selesai,
        'status'           => $request->status ?? $kegiatan->status,
        'periode_id'       => $request->periode_id ?? $kegiatan->periode_id,
        'surat_tugas'      => $kegiatan->surat_tugas, // Tambahkan ini
    ]);
    
    return response()->json([
        'status' => true,
        'message' => 'Data kegiatan berhasil diperbarui',
        'data' => $kegiatan
    ]);
}

public function store_ajax(Request $request)
{
    // Validasi data yang diterima dari request
    $rules = [
        'kategori_id'      => 'required|exists:kategori,kategori_id',
        'id_wilayah'       => 'required|exists:wilayah_kegiatan,id_wilayah',
        'kegiatan_nama'    => 'required|string|min:3|max:100',
        'deskripsi'        => 'nullable|string|max:500',
        'tanggal_mulai'    => 'required|date',
        'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
        'status'           => 'required|string|in:on progres,terlaksana',
        'periode_id'       => 'required|exists:periode_kegiatan,periode_id',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'status'   => false,
            'message'  => 'Validasi Gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $kegiatan = KegiatanModel::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data kegiatan berhasil disimpan',
            'data' => $kegiatan
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Gagal menyimpan data kegiatan',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function delete_ajax($id)
{
    $kegiatan = KegiatanModel::find($id);
    
    if (!$kegiatan) {
        return response()->json([
            'status'  => false,
            'message' => 'Data kegiatan tidak ditemukan'
        ], 404);
    }

    try {
        $kegiatan->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data kegiatan berhasil dihapus'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Gagal menghapus data kegiatan',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function show(string $id)
    {
        $kegiatan = KegiatanModel::with('kategori')->find($id);
        $breadcrumb = (object) ['title' => 'Detail Kegiatan', 'list' => ['Home', 'Kegiatan', 'Detail']];
        $page = (object) ['title' => 'Detail Kegiatan'];
        $activeMenu = 'kegiatan';
        return view('kegiatan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kegiatan' => $kegiatan, 'activeMenu' => $activeMenu]);
    }

    public function show_ajax(string $id)
    {
        $kegiatan = KegiatanModel::find($id);
    
        if (!$kegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Data kegiatan tidak ditemukan'
            ]);
        }
    
        // Mengembalikan tampilan dengan data kegiatan
        return view('kegiatan.show_ajax', ['kegiatan' => $kegiatan]);
    }
    

    public function confirm_ajax($id)
    {
        $kegiatan = KegiatanModel::find($id);
        return view('kegiatan.confirm_ajax', ['kegiatan' => $kegiatan]);
    }

    public function import()
    {
        return view('kegiatan.import');
    }
    
    public function import_ajax(Request $request)
    {
        $rules = [
            'file_kegiatan' => ['required', 'mimes:xlsx', 'max:1024'],
        ];
    
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }
    
        $file = $request->file('file_kegiatan');
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, false, true, true);
    
        $insert = [];
        if (count($data) > 1) {
            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    $insert[] = [
                        'kategori_id'      => $value['A'],
                        'id_wilayah'       => $value['B'], // Menambahkan id_wilayah
                        'kegiatan_nama'    => $value['C'],
                        'deskripsi'        => $value['D'],
                        'tanggal_mulai'    => $value['E'],
                        'tanggal_selesai'  => $value['F'],
                        'status'           => $value['G'],
                        'periode_id'       => $value['H'], // Menambahkan periode_id
                        'created_at'       => now(),
                    ];
                }
            }
        }
    
        if (count($insert) > 0) {
            KegiatanModel::insertOrIgnore($insert);
        }
    
        return redirect('/kegiatan');
    }
    
    public function export_excel()
    {
        // Ambil data kegiatan yang akan di-export
        $kegiatan = KegiatanModel::select('kategori_id', 'id_wilayah', 'kegiatan_nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'status', 'periode_id')
            ->orderBy('kategori_id')
            ->with('kategori', 'wilayah') // Ambil relasi kategori dan wilayah
            ->get();
        
        // Load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kategori Kegiatan');
        $sheet->setCellValue('C1', 'Wilayah');
        $sheet->setCellValue('D1', 'Nama Kegiatan');
        $sheet->setCellValue('E1', 'Deskripsi');
        $sheet->setCellValue('F1', 'Tanggal Mulai');
        $sheet->setCellValue('G1', 'Tanggal Selesai');
        $sheet->setCellValue('H1', 'Status');
        $sheet->setCellValue('I1', 'Periode'); // Menambahkan header untuk periode_id
        $sheet->getStyle('A1:I1')->getFont()->setBold(true); // Bold header
        
        $no = 1; // Nomor data dimulai dari 1
        $baris = 2; // Baris data dimulai dari baris ke 2
        foreach ($kegiatan as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->kategori->kategori_nama); // Ambil nama kategori
            $sheet->setCellValue('C' . $baris, $value->wilayah->nama_wilayah); // Ambil nama wilayah
            $sheet->setCellValue('D' . $baris, $value->kegiatan_nama);
            $sheet->setCellValue('E' . $baris, $value->deskripsi);
            $sheet->setCellValue('F' . $baris, $value->tanggal_mulai);
            $sheet->setCellValue('G' . $baris, $value->tanggal_selesai);
            $sheet->setCellValue('H' . $baris, $value->status);
            $sheet->setCellValue('I' . $baris, $value->periode_id); // Menambahkan periode_id
            $baris++;
            $no++;
        }
    
        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // Set auto size untuk kolom
        }
        
        $sheet->setTitle('Data Kegiatan'); // Set title sheet
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Kegiatan ' . date('Y-m-d H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified:' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $kegiatan = KegiatanModel::select('kategori_id', 'id_wilayah', 'kegiatan_nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'status', 'periode_id')
            ->orderBy('kategori_id')
            ->orderBy('kegiatan_nama')
            ->with('kategori', 'wilayah') // Ambil relasi kategori dan wilayah
            ->get();
        
        // Generate PDF
        $pdf = Pdf::loadView('kegiatan.export_pdf', ['kegiatan' => $kegiatan]);
        $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // Set true jika ada gambar dari URL
        return $pdf->stream('Data Kegiatan ' . date('Y-m-d H:i:s') . '.pdf');
    }
    

    
}
 
