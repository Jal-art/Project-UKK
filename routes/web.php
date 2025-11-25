<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

//root
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login.show');
})->name('home');


//auth
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');


//halaman kasir
Route::middleware('auth')->group(function () {

    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    //produk
    Route::get('/produk',               [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/create',        [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk',              [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{produk}',      [ProdukController::class, 'show'])->name('produk.show');
    Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{produk}',      [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{produk}',   [ProdukController::class, 'destroy'])->name('produk.destroy');


    //transaksi
    Route::get('/transaksi',              [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/create',       [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi',             [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{transaksi}',  [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::delete('/transaksi/{transaksi}',[TransaksiController::class, 'destroy'])->name('transaksi.destroy');

    // Struk
    Route::get('/transaksi/{transaksi}/struk', [TransaksiController::class, 'struk'])
        ->name('transaksi.struk');


    //laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});


//fallback route
Route::fallback(function () {
    return redirect()->route('login.show');
});
