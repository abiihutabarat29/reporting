<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\UserDesaController;
use App\Http\Controllers\UserKecController;
use Illuminate\Support\Facades\Route;

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

Route::controller(LoginController::class)->group(function () {
    // Login
    Route::get('/', 'index')->name('index')->middleware('guest');
    Route::post('/', 'login')->name('login');
    Route::get('logout', 'logout')->name('logout');
});

Route::group(['middleware' => ['auth:admin,admkec,admdesa']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::group(['middleware' => ['role:1']], function () {
        Route::resource('kecamatan', KecamatanController::class);
        Route::resource('desa', DesaController::class);
        Route::post('desa/get-desa', [DesaController::class, 'getDesa']);
        Route::resource('user-kecamatan', UserKecController::class);
        Route::resource('user-desa', UserDesaController::class);
        Route::resource('bidang', BidangController::class);
        Route::resource('program-kerja', ProgramController::class);
        Route::resource('kegiatan', KegiatanController::class);
    });
});
