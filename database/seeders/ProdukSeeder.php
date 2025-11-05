<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['nama_produk' => 'Nike',   'ukuran' => 'XL', 'warna' => 'Hitam', 'stok' => 40, 'harga' => 50000],
            ['nama_produk' => 'Adidas', 'ukuran' => 'L',  'warna' => 'Hitam', 'stok' => 20, 'harga' => 70000],
            ['nama_produk' => 'Uniqlo', 'ukuran' => 'M',  'warna' => 'Putih', 'stok' => 35, 'harga' => 120000],
            ['nama_produk' => 'Puma',   'ukuran' => 'S',  'warna' => 'Biru',  'stok' => 25, 'harga' => 95000],
            ['nama_produk' => 'Levi’s', 'ukuran' => '32', 'warna' => 'Navy',  'stok' => 15, 'harga' => 250000],
            ['nama_produk' => 'H&M',    'ukuran' => 'M',  'warna' => 'Abu',   'stok' => 30, 'harga' => 85000],
            ['nama_produk' => 'Zara',   'ukuran' => 'L',  'warna' => 'Cream', 'stok' => 18, 'harga' => 175000],
        ];

        foreach ($items as $i) {
            Produk::create($i);
        }
    }
}
