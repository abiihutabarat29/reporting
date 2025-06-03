<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LaporanController;
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
Route::get('reload-captcha', [LoginController::class, 'reload']);
Route::group(['middleware' => ['auth', 'role:1,2,3']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kegiatan-kabupaten', [LaporanController::class, 'laporankab'])->name('laporan.kabupaten');
    Route::get('/kegiatan-kecamatan', [LaporanController::class, 'laporankec'])->name('laporan.kecamatan');
    Route::get('/kegiatan-desa-kelurahan', [LaporanController::class, 'laporandesa'])->name('laporan.desa');
    Route::get('/profil', [UserController::class, 'profil'])->name('profil');
    Route::get('/profil/edit', [UserController::class, 'profiledit'])->name('profil.edit');
    Route::put('profil/{id}/update-profi', [UserController::class, 'updateprofil'])->name('profil.update.profil');
    Route::put('profil/{id}/update-foto', [UserController::class, 'updatefoto'])->name('profil.update.foto');
    Route::put('profil/{id}/update-banner', [UserController::class, 'updatebanner'])->name('profil.update.banner');
    Route::put('profil/{id}/update-password', [UserController::class, 'updatepassword'])->name('profil.update.password');
    Route::post('program/get-program', [ProgramController::class, 'getProgram']);
    Route::post('kegiatan/get-kegiatan', [KegiatanController::class, 'getKegiatan']);
    Route::resource('anggota', AnggotaController::class);
    Route::resource('laporan', LaporanController::class)->middleware('checkProfil');;
    Route::post('/laporan-kegiatan-pdf', [LaporanController::class, 'pdf'])->name('laporan-kegiatan-pdf');
    Route::get('/ranking-kecamatan', [LaporanController::class, 'rankingkec'])->name('ranking-kecamatan');
    Route::get('/ranking-desa-kelurahan', [LaporanController::class, 'rankingdesa'])->name('ranking-desa-kelurahan');
    // Route::get('/get-sk-image', [AnggotaController::class, 'getSKImage']);
    Route::get('/get-sk/{id}', [AnggotaController::class, 'getSK'])->name('get.sk');
    Route::get('/keanggotaan-kecamatan', [AnggotaController::class, 'JumlahAnggotaKec'])->name('keanggotaan-kecamatan');
    Route::get('/keanggotaan-desa-kelurahan', [AnggotaController::class, 'JumlahAnggotaDesa'])->name('keanggotaan-desa-kelurahan');
    Route::get('/download-buku-panduan', [LaporanController::class, 'download'])->name('download-buku-panduan');
});

Route::group(['middleware' => ['auth', 'role:1']], function () {
    Route::resource('kecamatan', KecamatanController::class);
    Route::resource('desa', DesaController::class);
    Route::post('desa/get-desa', [DesaController::class, 'getDesa']);
    Route::resource('user', UserController::class);
    Route::resource('bidang', BidangController::class);
    Route::resource('program-kerja', ProgramController::class);
    Route::resource('program-kerja', ProgramController::class);
    Route::resource('kegiatan', KegiatanController::class);
    Route::get('/keanggotaan-kecamatan-detail', [AnggotaController::class, 'detailkec'])->name('keanggotaan-kecamatan-detail');
    Route::get('/keanggotaan-desa-detail', [AnggotaController::class, 'detaildesa'])->name('keanggotaan-desa-detail');
    Route::get('/laporan-kecamatan-detail', [LaporanController::class, 'lapdetailkec'])->name('laporan-kecamatan-detail');
    Route::get('/laporan-desa-detail', [LaporanController::class, 'lapdetaildesa'])->name('laporan-desa-detail');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
