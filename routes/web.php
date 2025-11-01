<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Rute Publik (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/pcr', function () {
    return 'selamat datang di website kampus PCR!';
});
Route::get('/mahasiswa', function () {
    return 'hallo mahasiswa!';
});
// ... (rute-rute publikmu yang lain) ...
Route::get('/nama/{param1}', function ($param1) {
    return 'nama saya: ' . $param1;
});
Route::get('/nim/{param1?}', function ($param1 = '') {
    return 'nim saya: ' . $param1;
});
Route::get('/mahasiswa/{param1?}', [MahasiswaController::class, 'show']);
Route::get('/about', function () {
    return view('halaman-about');
});
Route::get('/matakuliah', [MatakuliahController::class, 'index']);
Route::get('/matakuliah/show/{kode?}', [MatakuliahController::class, 'show']);
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('question/store', [QuestionController::class, 'store'])
    ->name('question.store');

/*
|--------------------------------------------------------------------------
| Rute Autentikasi (Login & Logout)
|--------------------------------------------------------------------------
*/
// Menampilkan form login
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
// Memproses data login
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
// Memproses logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pelanggan
    Route::resource('pelanggan', PelangganController::class);

    // User
    Route::resource('user', UserController::class);

    // Jika mau halaman root (/) setelah login ke dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

});
