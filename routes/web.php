<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\TfAntarBankController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('/tes', fn() => 'halo');

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', fn(\Illuminate\Http\Request $r, AuthController $c) => $c->register($r, 'user'))->name('register.post');
Route::get('/adm_regist', [AuthController::class, 'admRegistForm'])->name('adm_regist');
Route::post('/adm_regist', fn(\Illuminate\Http\Request $r, AuthController $c) => $c->register($r, 'admin'))->name('adm_regist.post');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Uang masuk/keluar bisa dilihat user (read-only), CRUD dibatasi admin di controller/view
    Route::get('/uang-masuk', [KeuanganController::class, 'masuk'])->name('masuk.index');
    Route::get('/uang-keluar', [KeuanganController::class, 'keluar'])->name('keluar.index');

    // Profil: semua user login bisa akses
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

    Route::middleware('role:admin')->group(function () {
        Route::post('/uang-masuk', [KeuanganController::class, 'store'])->name('masuk.store');
        Route::put('/keuangan/{keuangan}', [KeuanganController::class, 'update'])->name('keuangan.update');
        Route::delete('/keuangan/{keuangan}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');

        Route::get('/bank', [BankController::class, 'index'])->name('bank.index');
        Route::post('/bank', [BankController::class, 'store'])->name('bank.store');
        Route::post('/bank/proses-adm', [BankController::class, 'prosesAdm'])->name('bank.proses-adm');
        Route::put('/bank/{bank}', [BankController::class, 'update'])->name('bank.update');
        Route::delete('/bank/{bank}', [BankController::class, 'destroy'])->name('bank.destroy');

        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        Route::get('/tf-antar-bank', [TfAntarBankController::class, 'index'])->name('tf.index');
        Route::post('/tf-antar-bank', [TfAntarBankController::class, 'store'])->name('tf.store');
        Route::put('/tf-antar-bank/{tfAntarBank}', [TfAntarBankController::class, 'update'])->name('tf.update');
        Route::delete('/tf-antar-bank/{tfAntarBank}', [TfAntarBankController::class, 'destroy'])->name('tf.destroy');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/excel', [LaporanController::class, 'excel'])->name('laporan.excel');
        Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
