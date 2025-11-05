<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;

/**
 * Root: ke login bila belum login, atau ke dashboard bila sudah.
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

    // Produk aktif (sidebar mengarah ke route ini)
    Route::resource('produk', ProdukController::class)
         ->parameters(['produk' => 'produk']);
});

/** Fallback */
Route::fallback(function () {
    return redirect()->route('login.show');
});
