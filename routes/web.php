<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\UserLevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestoreEditController;
use App\Http\Controllers\RestoreDeleteController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\LaporanController;
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

Route::get('/dashboard', [Controller::class, 'dashboard'])->name('dashboard');
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/aksi_login', [LoginController::class, 'aksi_login'])->name('aksi_login');
Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/tambah_akun', [LoginController::class, 'tambah_akun'])->name('tambah_akun');
Route::get('/captcha', [LoginController::class, 'captcha'])->name('captcha');



// ROUTE SETTING
Route::get('settings', [SettingController::class, 'edit'])
    ->middleware('check.permission:setting')
    ->name('settings.edit');
Route::post('settings', [SettingController::class, 'update'])
    ->name('settings.update');

// ROUTE LOG ACTIVITY
Route::get('log', [LogController::class, 'index'])
    ->middleware('check.permission:setting')
    ->name('log');

// ROUTE PERMISSION
Route::get('/user-levels', [UserLevelController::class, 'index'])
    ->middleware('check.permission:setting')
    ->name('user.levels');
Route::get('/menu-permissions/{userLevel}', [UserLevelController::class, 'showMenuPermissions'])
    ->name('menu.permissions');
Route::post('/save-permissions', [UserLevelController::class, 'savePermissions'])
    ->name('save.permissions');

// ROUTE RESTORE EDIT
Route::get('/restore_e', [RestoreEditController::class, 'restore_e'])
    ->middleware('check.permission:setting')
    ->name('restore_e');
Route::post('/user/restore/{id_user}', [RestoreEditController::class, 'restoreEdit'])->name('user.restoreEdit');
Route::delete('/user_history/{id_user_history}', [RestoreEditController::class, 're_destroy'])->name('re.destroy');

// ROUTE RESTORE DELETE
Route::get('/restore_d', [RestoreDeleteController::class, 'restore_d'])
    ->middleware('check.permission:setting')
    ->name('restore_d');
Route::post('/user/restore-delete/{id}', [RestoreDeleteController::class, 'user_restore'])->name('user.restore');
Route::delete('/user/{id}', [RestoreDeleteController::class, 'rd_destroy'])->name('rd.destroy');

// ROUTE USER
Route::get('/user', [UserController::class, 'user'])
    ->middleware('check.permission:setting')
    ->name('user');
Route::post('/t_user', [UserController::class, 't_user'])->name('t_user');
Route::post('/user/reset-password/{id}', [UserController::class, 'resetPassword'])->name('user.resetPassword');
Route::post('/user/update', [UserController::class, 'updateDetail'])->name('update.user');
Route::delete('/user-destroy/{id_user}', [UserController::class, 'user_destroy'])->name('user.destroy');
Route::get('/user/detail/{id}', [UserController::class, 'detail'])->name('detail');

// ROUTE PERIODE
Route::get('/periode', [PeriodeController::class, 'periode'])
    ->name('periode');
Route::post('buat_periode', [PeriodeController::class, 'buat_periode'])
    ->name('buat_periode');
Route::delete('/periode/{id_periode}', [PeriodeController::class, 'periode_destroy'])->name('periode.destroy');
Route::get('/periode/update/{id}', [PeriodeController::class, 'update'])->name('periode.update');
Route::get('/periode/buka/{id}', [PeriodeController::class, 'aktif'])->name('periode.aktif');
Route::get('/periode/tidak_aktif/{id}', [PeriodeController::class, 'tidak_aktif'])->name('periode.tidak_aktif');


// ROUTE GURU
Route::get('/guru', [GuruController::class, 'guru'])
    ->name('guru');
Route::post('buat_guru', [GuruController::class, 'buat_guru'])
    ->name('buat_guru');
Route::delete('/guru/{id_guru}', [GuruController::class, 'guru_destroy'])->name('guru.destroy');
Route::get('/guru/update/{id}', [GuruController::class, 'update'])->name('guru.update');


// ROUTE ULASAN
Route::get('/ulasan', [UlasanController::class, 'ulasan'])
    ->name('ulasan');
Route::get('buat_ulasan', [UlasanController::class, 'buat_ulasan'])
    ->name('buat_ulasan');
Route::get('/ulasan/ganti', [UlasanController::class, 'gantiUlasan'])->name('ganti_ulasan');

// ROUTE HISTORY
Route::get('/history', [UlasanController::class, 'history'])
    ->name('history');
    Route::get('/ulasan_saya', [UlasanController::class, 'ulasan_saya'])
    ->name('ulasan_saya');

// ROUTE ULASAN_GURU
Route::get('/ulasan_guru', [UlasanController::class, 'ulasan_guru'])
    ->name('ulasan_guru');

// ROUTE LAPORAN
Route::get('/print_laporan', [LaporanController::class, 'print_laporan'])
    ->name('print_laporan');
Route::get('/printLaporan', [LaporanController::class, 'printLaporan'])->name('printLaporan');
