<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Auth (login/logout)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// "/" langsung ke dashboard (kalau belum login, middleware akan arahkan ke /login)
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// Hanya yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Nanti tambahkan menu lain di sini:
    // Route::resource('produk', ProdukController::class);
    // Route::resource('transaksi', TransaksiController::class);
    // Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
});
