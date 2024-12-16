<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\KegiatanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KegiatanController extends Controller
{
    public function __invoke(Request $request)
    {
        $action = $request->get('action', 'index');
        switch ($action) {
            case 'index':
                return $this->index();
            case 'store':
                return $this->store($request);
            case 'show':
                return $this->show($request->get('id'));
            case 'update':
                return $this->update($request, $request->get('id'));
            case 'destroy':
                return $this->destroy($request->get('id'));
            default:
                return response()->json(['error' => 'Aksi tidak valid'], 400);
        }
    }

    protected function index()
    {
        $kegiatan = KegiatanModel::all();
        return response()->json($kegiatan, 200);
    }
}
