<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil angka dari DB jika tabel sudah ada, kalau belum ada → fallback angka demo
        try {
            $stokCount      = (int) (DB::table('produks')->sum('stok') ?? 0);
            $transaksiCount = (int) (DB::table('transaksis')->count() ?? 0);
            $terjualCount   = (int) (DB::table('detail_transaksis')->sum('jumlah') ?? 0);
        } catch (\Throwable $e) {
            $stokCount = 400; $transaksiCount = 30; $terjualCount = 40;
        }

        return view('kasir.dashboard', compact('stokCount','transaksiCount','terjualCount'));
    }
}
