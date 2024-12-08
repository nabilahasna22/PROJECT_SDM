<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\KegiatanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaftarKegiatanController;
use App\Http\Controllers\DetailKegiatanController;
use App\Http\Controllers\ProgresController;
use App\Http\Controllers\ProfileController;
use Database\Seeders\KegiatanSeeder;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::pattern('id', '[0-9]+');

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function () {

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);          // menampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']);      // menampilkan data user dalam bentuk json untuk datatables
    Route::get('/create', [UserController::class, 'create']);   // menampilkan halaman form tambah user
    Route::post('/', [UserController::class, 'store']);         // menyimpan data user baru
    Route::get('/{id}', [UserController::class, 'show']);       // menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']);  // menampilkan halaman form edit user
    Route::put('/{id}', [UserController::class, 'update']);     // menyimpan perubahan data user
    Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
});
Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index']);          // menampilkan halaman awal level
    Route::post('/list', [LevelController::class, 'list']);      // menampilkan data level dalam bentuk json untuk datatables
    Route::get('/create', [LevelController::class, 'create']);   // menampilkan halaman form tambah level
    Route::post('/', [LevelController::class, 'store']);         // menyimpan data level baru
    Route::get('/{id}', [LevelController::class, 'show']);       // menampilkan detail level
    Route::get('/{id}/edit', [LevelController::class, 'edit']);  // menampilkan halaman form edit level
    Route::put('/{id}', [LevelController::class, 'update']);     // menyimpan perubahan data level
    Route::delete('/{id}', [LevelController::class, 'destroy']); // menghapus data level
});

Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', [KategoriController::class, 'index']);          // menampilkan halaman awal level
    Route::post('/list', [KategoriController::class, 'list']);      // menampilkan data level dalam bentuk json untuk datatables
    Route::get('/create', [KategoriController::class, 'create']);   // menampilkan halaman form tambah level
    Route::post('/', [KategoriController::class, 'store']);         // menyimpan data level baru
    Route::get('/{id}', [KategoriController::class, 'show']);       // menampilkan detail level
    Route::get('/{id}/edit', [KategoriController::class, 'edit']);  // menampilkan halaman form edit level
    Route::put('/{id}', [KategoriController::class, 'update']);     // menyimpan perubahan data level
    Route::delete('/{id}', [KategoriController::class, 'destroy']); // menghapus data level
});

Route::group(['prefix' => 'kegiatan'], function () {
    Route::get('/', [KegiatanController::class, 'index']);          // menampilkan halaman awal level
    Route::post('/list', [KegiatanController::class, 'list']);      // menampilkan data level dalam bentuk json untuk datatables
    Route::get('/create', [KegiatanController::class, 'create']);   // menampilkan halaman form tambah level
    Route::get('/create_ajax', [KegiatanController::class, 'create_ajax']);
    Route::post('/', [KegiatanController::class, 'store']);         // menyimpan data level baru
    Route::get('/{id}/edit_ajax', [KegiatanController::class, 'edit_ajax']);  // menampilkan halaman form edit level
    Route::put('/{id}/update_ajax', [KegiatanController::class, 'update_ajax']);     // menyimpan perubahan data level
    Route::post('/ajax', [KegiatanController::class, 'store_ajax']);         // menyimpan data level baru
    Route::get('/{id}/delete_ajax', [KegiatanController::class, 'confirm_ajax']); // menampilkan konfirmasi hapus kategori via Ajax
    Route::delete('/{id}/delete_ajax', [KegiatanController::class, 'delete_ajax']); // menghapus data kategori via Ajax
    Route::get('/import', [KegiatanController::class, 'import']); //ajax form upload excel
    Route::post('/import_ajax', [KegiatanController::class, 'import_ajax']); //ajax form upload excel
    Route::get('/export_excel', [KegiatanController::class, 'export_excel']); //export excel
    Route::get('/export_pdf', [KegiatanController::class, 'export_pdf']); //export excel
    //Route::get('/{id}', [KegiatanController::class, 'show']);       // menampilkan detail level
    Route::get('/{id}/show_ajax', [KegiatanController::class, 'show_ajax']);  // menampilkan halaman form edit level
    Route::delete('/{id}', [KegiatanController::class, 'destroy']); // menghapus data level
});
Route::group(['prefix' => 'daftar_kegiatan'], function () {
    Route::get('/', [DaftarKegiatanController::class, 'index']);          // menampilkan halaman awal level
    Route::get('/kegiatan/user', [DaftarKegiatanController::class, 'kegiatanUser']);

});
Route::group(['prefix' => 'progres'], function () {
    Route::get('/', [ProgresController::class, 'index']);          // menampilkan halaman awal level
    Route::post('/list', [ProgresController::class, 'list']);      // menampilkan data level dalam bentuk json untuk datatables
    Route::get('/create', [ProgresController::class, 'create']);   // menampilkan halaman form tambah level
    Route::post('/', [ProgresController::class, 'store']);         // menyimpan data level baru
    Route::get('/{id}', [ProgresController::class, 'show']);       // menampilkan detail level
    Route::get('/{id}/edit', [ProgresController::class, 'edit']);  // menampilkan halaman form edit level
    Route::put('/{id}', [ProgresController::class, 'update']);     // menyimpan perubahan data level
    Route::delete('/{id}', [ProgresController::class, 'destroy']); // menghapus data level
});
Route::group(['prefix' => 'detailkegiatan'], function () {
    Route::get('/', [DetailKegiatanController::class, 'index']);          // menampilkan halaman awal level
    Route::post('/list', [DetailKegiatanController::class, 'list']);      // menampilkan data level dalam bentuk json untuk datatables
    Route::get('/create', [DetailKegiatanController::class, 'create']);   // menampilkan halaman form tambah level
    Route::post('/', [DetailKegiatanController::class, 'store']);         // menyimpan data level baru
    Route::get('/{id}', [DetailKegiatanController::class, 'show']);       // menampilkan detail level
    Route::get('/{id}/edit', [DetailKegiatanController::class, 'edit']);  // menampilkan halaman form edit level
    Route::put('/{id}', [DetailKegiatanController::class, 'update']);     // menyimpan perubahan data level
    Route::delete('/{id}', [DetailKegiatanController::class, 'destroy']); // menghapus data level
});
Route::group(['prefix' => 'profile'], function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/{nip}/edit_ajax', [ProfileController::class, 'edit_ajax'])->name('profile.edit_ajax'); // Sesuaikan dengan nip
    Route::put('/{nip}/update_ajax', [ProfileController::class, 'update_ajax'])->name('profile.update_ajax'); // Sesuaikan dengan nip
});
});