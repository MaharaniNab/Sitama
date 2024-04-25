<?php

use App\Http\Controllers\DBBackupController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\TaController;
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

Route::post('/bimbingan/verify', 'BimbinganController@verify');
Route::put('/bimbingan/verify', 'BimbinganController@verify')->name('bimbingan.verify');
Route::get('/bimbingan/create', 'BimbinganController@create')->name('bimbingan.create');
Route::get('/bimbingan/destroy', 'BimbinganController@destroy')->name('bimbingan.destroy');
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
