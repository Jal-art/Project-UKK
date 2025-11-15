<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

/**
 * Root
 */
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login.show');
})->name('home');

/** Auth */
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/** Hanya user login */
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Produk
    Route::resource('produk', ProdukController::class)
         ->parameters(['produk' => 'produk']);

    // Transaksi
    Route::resource('transaksi', TransaksiController::class)
         ->parameters(['transaksi' => 'transaksi'])
         ->except(['edit','update']);

    // Struk transaksi
    Route::get('/transaksi/{transaksi}/struk', [TransaksiController::class, 'struk'])
         ->name('transaksi.struk');

    // LAPORAN (index saja)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

/** Fallback */
Route::fallback(function () {
    return redirect()->route('login.show');
});
