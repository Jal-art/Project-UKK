<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    // Sesuai migration
    protected $table = 'produks';
    protected $primaryKey = 'id_produk';
    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang bisa diisi mass-assignment
    protected $fillable = [
        'nama_produk', 'ukuran', 'warna', 'harga', 'stok',
    ];

    // Casting harga biar enak dipakai sebagai decimal/float
    protected $casts = [
        'harga' => 'decimal:2',
        'stok'  => 'integer',
    ];
}
