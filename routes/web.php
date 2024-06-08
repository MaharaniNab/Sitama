<?php

use App\Http\Controllers\DBBackupController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\MahasiswaBimbinganController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\TaController;
use App\Http\Controllers\UjianSidangController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::permanentRedirect('/', '/login');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/bimbingan', [BimbinganController::class, 'index'])->name('bimbingan');
Route::resource('profil', ProfilController::class)->except('destroy');


Route::get('/ujian-sidang', [UjianSidangController::class, 'index'])->name('ujian-sidang.index');
Route::get('ujian-sidang/kelayakan/{ta_id}', [UjianSidangController::class, 'kelayakan'])->name('ujian-sidang.kelayakan');
Route::get('ujian-sidang/penguji/{ta_id}', [UjianSidangController::class, 'penguji'])->name('ujian-sidang.penguji');
Route::get('ujian-sidang/revisi/{ta_id}', [UjianSidangController::class, 'showRevisi'])->name('ujian-sidang.revisi');
Route::get('ujian-sidang/revisi2/{ta_id}', [UjianSidangController::class, 'showRevisi2'])->name('ujian-sidang.revisi2');
Route::post('/ujian-sidang/kelayakan/{ta_id}', [App\Http\Controllers\UjianSidangController::class, 'storeKelayakan'])->name('ujian-sidang.storeKelayakan');
Route::post('/ujian-sidang/penguji/{ta_id}', [App\Http\Controllers\UjianSidangController::class, 'storePenguji'])->name('ujian-sidang.storePenguji');


Route::get('/mhsbimbingan', [MahasiswaBimbinganController::class, 'index'])->name('mhsbimbingan.index');
Route::post('/setujui-sidang-akhir/{ta_id}', [MahasiswaBimbinganController::class, 'setujuiSidangAkhir'])->name('setujui.sidang.akhir');
Route::post('/setujui-pembimbingan/{ta_id}', [MahasiswaBimbinganController::class, 'setujuiPembimbingan'])->name('setujui-pembimbingan');
Route::get('/mhsbimbingan/{ta_id}', [MahasiswaBimbinganController::class, 'pembimbingan'])->name('mhsbimbingan.pembimbingan');
Route::resource('mhsbimbingan', MahasiswaBimbinganController::class);

Route::resource('bimbingan', BimbinganController::class);
Route::post('/bimbingan/verify', [BimbinganController::class, 'verify'])->name('bimbingan.verify');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/bimbingan', [BimbinganController::class, 'index'])->name('bimbingan');
Route::get('/ta', [TaController::class, 'index'])->name('ta');
Route::resource('profil', ProfilController::class)->except('destroy');

Route::resource('manage-user', UserController::class);
Route::resource('manage-role', RoleController::class);
Route::resource('manage-menu', MenuController::class);
Route::resource('manage-permission', PermissionController::class)->only('store', 'destroy');
Route::resource('bimbingan', BimbinganController::class);
Route::resource('ta', TaController::class);

Route::get('dbbackup', [DBBackupController::class, 'DBDataBackup']);