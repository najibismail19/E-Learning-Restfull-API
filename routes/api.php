<?php

use App\Http\Controllers\MahasiswaController;
use App\Http\Middleware\ApiAuthMahasiswaMiddleware;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post("/mahasiswa", [MahasiswaController::class, "register"]);
Route::post("/mahasiswa/login", [MahasiswaController::class, "login"]);

Route::middleware(ApiAuthMahasiswaMiddleware::class)->group(function () {
    Route::post("/mahasiswa/logout", [MahasiswaController::class, "logout"]);
    Route::post("/mahasiswa/current", [MahasiswaController::class, "get"]);
});