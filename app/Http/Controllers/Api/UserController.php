<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User; // Pastikan model User sudah dibuat
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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
                return $this->show($request->get('nip'));
            case 'update':
                return $this->update($request, $request->get('nip'));
            case 'destroy':
                return $this->destroy($request->get('nip'));
            default:
                return response()->json(['error' => 'Aksi tidak valid'], 400);
        }
    }

    protected function index()
    {
        $users = UserModel::all();
        return response()->json($users, 200);
    }

    protected function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip'       => 'required|string|unique:users,nip|min:8|max:20',
            'level_id'  => 'required|integer',
            'username'  => 'required|string|min:3|max:20',
            'nama'      => 'required|string|min:3|max:100',
            'email'     => 'required|email|max:100|unique:users,email',
            'no_telp'   => 'nullable|string|digits_between:10,15',
            'alamat'    => 'nullable|string|max:255',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,ico,bmp|max:2048',
            'password'  => 'required|string|min:5|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('users', 'public');
        }

        $user = User::create($data);

        return response()->json([
            'success' => true,
            'data' => $user,
        ], 201);
    }

    protected function show($nip)
    {
        $user = User::find($nip);
        if (!$user) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($user, 200);
    }

    protected function update(Request $request, $nip)
    {
        $user = User::find($nip);
        if (!$user) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'level_id'  => 'sometimes|required|integer',
            'username'  => 'sometimes|required|string|min:3|max:20',
            'nama'      => 'sometimes|required|string|min:3|max:100',
            'email'     => 'sometimes|required|email|max:100|unique:users,email,' . $nip . ',nip',
            'no_telp'   => 'nullable|string|digits_between:10,15',
            'alamat'    => 'nullable|string|max:255',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,ico,bmp|max:2048',
            'password'  => 'nullable|string|min:5|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();
        if ($request->filled('password')) {
            $data['password'] = bcrypt($data['password']);
        }
        
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('users', 'public');
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'data' => $user,
        ], 200);
    }

    protected function destroy($nip)
    {
        $user = User::find($nip);
        if (!$user) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Terhapus',
        ], 200);
    }
}