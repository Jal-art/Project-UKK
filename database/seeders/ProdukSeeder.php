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
        ];

        foreach ($items as $i) {
            Produk::create($i);
        }
    }
}
