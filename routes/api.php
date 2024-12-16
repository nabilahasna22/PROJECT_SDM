<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('all_data', [\App\Http\Controllers\Api\KegiatanController::class, 'index']);             // Menampilkan daftar kegiatan
Route::post('login', [\App\Http\Controllers\Api\RegisterController::class, 'index']); 
Route::post('all_user', [\App\Http\Controllers\Api\UserController::class, 'index']); 
Route::post('save', [\App\Http\Controllers\Api\UserController::class, 'store']); 
Route::post('detail', [\App\Http\Controllers\Api\UserController::class, 'show']); 
Route::post('edit', [\App\Http\Controllers\Api\UserController::class, 'update']); 
Route::post('delete', [\App\Http\Controllers\Api\UserController::class, 'destroy']); 


