<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\UserController;
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

Route::view('/', 'auth.login')->name('login');
Auth::routes([
    'register' => false, // Register Routes...
    'reset' => false, // Reset Password Routes...
    'verify' => false, // Email Verification Routes...
]);


Route::group(['middleware' => ['role:1,2,3']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::put('profil/{id}/update-foto', [ProfilController::class, 'updatefoto'])->name('profil.update.foto');
    Route::put('profil/{id}/update-password', [ProfilController::class, 'updatepassword'])->name('profil.update.password');
});

Route::group(['middleware' => ['role:1']], function () {
    Route::resource('kecamatan', KecamatanController::class);
    Route::resource('desa', DesaController::class);
    Route::post('desa/get-desa', [DesaController::class, 'getDesa']);
    Route::resource('user', UserController::class);
    Route::resource('bidang', BidangController::class);
    Route::resource('program-kerja', ProgramController::class);
    Route::resource('kegiatan', KegiatanController::class);
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
