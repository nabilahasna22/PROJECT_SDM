<?php
namespace App\Http\Controllers;

use App\Models\KegiatanModel;
use App\Models\KategoriModel;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
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

    // Mengirim semua data ke view
    return view('kegiatan.index', [
        'activeMenu'  => $activeMenu,
        'breadcrumb'  => $breadcrumb,
        'kategori'    => $kategori,
        'wilayah'     => $wilayah
    ]);
    }


    public function list(Request $request)
    {
        $kegiatan = KegiatanModel::select('kategori_id', 'kegiatan_id', 'kegiatan_nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'status', 'id_wilayah')
            ->with(['kategori', 'wilayah']); // Tambahkan relasi 'wilayah'
    
        $kategori_id = $request->input('filter_kategori');
        if (!empty($kategori_id)) {
            $kegiatan->where('kategori_id', $kategori_id);
        }
        $id_wilayah = $request->input('filter_wilayah');
        if (!empty($id_wilayah)) {
            $kegiatan->where('id_wilayah', $id_wilayah);
        }
    
        return DataTables::of($kegiatan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kegiatan) {
                $btn = '<a href="' . url('/kegiatan/' . $kegiatan->kegiatan_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<button onclick="modalAction(\''.url('/kegiatan/'. $kegiatan->kegiatan_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button>';
                $btn .= '<button onclick="modalAction(\''.url('/kegiatan/' . $kegiatan->kegiatan_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    

    public function create_ajax()
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
        return view('kegiatan.create_ajax')->with('kategori', $kategori);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id'     => ['required', 'integer', 'exists:kategori,kategori_id'],
                'kegiatan_nama'   => ['required', 'string', 'max:100'],
                'deskripsi'       => ['nullable', 'string'],
                'tanggal_mulai'   => ['required', 'date'],
                'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
                'status'          => ['required', 'string', 'in:planned,ongoing,completed']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            KegiatanModel::create($request->all());
            return response()->json([
                'status'  => true,
                'message' => 'Data kegiatan berhasil disimpan'
            ]);
        }
        return redirect('/');
    }
    

    public function show(string $id)
    {
        $kegiatan = KegiatanModel::with('kategori')->find($id);
        $breadcrumb = (object) ['title' => 'Detail Kegiatan', 'list' => ['Home', 'Kegiatan', 'Detail']];
        $page = (object) ['title' => 'Detail Kegiatan'];
        $activeMenu = 'kegiatan';
        return view('kegiatan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kegiatan' => $kegiatan, 'activeMenu' => $activeMenu]);
    }
    public function confirm_ajax($id)
    {
        $kegiatan = KegiatanModel::find($id);
        return view('kegiatan.confirm_ajax', ['kegiatan' => $kegiatan]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kegiatan = KegiatanModel::find($id);
            if ($kegiatan) {
                $kegiatan->delete();
                return response()->json([
                    'status'  => true,
                    'message' => 'Data kegiatan berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data kegiatan tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function import()
    {
        return view('kegiatan.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
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
                            'kategori_id'     => $value['A'],
                            'kegiatan_nama'   => $value['B'],
                            'deskripsi'       => $value['C'],
                            'tanggal_mulai'   => $value['D'],
                            'tanggal_selesai' => $value['E'],
                            'status'          => $value['F'],
                            'jenis_kegiatan'  => $value['G'],
                            'created_at'      => now(),
                        ];
                    }
                }
            }

            if (count($insert) > 0) {
                KegiatanModel::insertOrIgnore($insert);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Data kegiatan berhasil diimport'
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }
        return redirect('/');
    }

    public function export_excel()
    {
        // ambil data kegiatan yang akan di-export
        $kegiatan = KegiatanModel::select('kategori_id', 'kegiatan_id', 'kegiatan_nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai')
            ->orderBy('kategori_id')
            ->with('kategori')
            ->get();
        
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Kegiatan');
        $sheet->setCellValue('C1', 'Deskripsi');
        $sheet->setCellValue('D1', 'Tanggal Mulai');
        $sheet->setCellValue('E1', 'Tanggal Selesai');
        $sheet->setCellValue('F1', 'Kategori');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // bold header
        
        $no = 1; // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach ($kegiatan as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->kegiatan_nama);
            $sheet->setCellValue('C' . $baris, $value->deskripsi);
            $sheet->setCellValue('D' . $baris, $value->tanggal_mulai);
            $sheet->setCellValue('E' . $baris, $value->tanggal_selesai);
            $sheet->setCellValue('F' . $baris, $value->kategori->kategori_nama); // ambil nama kategori
            $baris++;
            $no++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }
        
        $sheet->setTitle('Data Kegiatan'); // set title sheet
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
        $kegiatan = KegiatanModel::select('kategori_id', 'kegiatan_id', 'kegiatan_nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai')
            ->orderBy('kategori_id')
            ->orderBy('kegiatan_nama')
            ->with('kategori')
            ->get();
        // Generate PDF
        $pdf = Pdf::loadView('kegiatan.export_pdf', ['kegiatan' => $kegiatan]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        return $pdf->stream('Data Kegiatan ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
 
