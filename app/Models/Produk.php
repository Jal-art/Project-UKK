<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    // ====== SESUAI MIGRATIONMU ======
    // Tabel & Primary Key
    protected $table = 'produks';            // ubah jika tabelmu beda
    protected $primaryKey = 'id_produk';     // pk di migrationmu

    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;               // set false kalau tabelmu tanpa timestamps

    // Mass assignment
    protected $fillable = ['nama_produk','ukuran','warna','harga','stok'];

    // Casting tipe data angka
    protected $casts = [
        'harga' => 'integer',
        'stok'  => 'integer',
    ];

    // Pencarian nama saja (permintaanmu)
    public function scopeCari($q, $term)
    {
        $term = trim((string) $term);
        if ($term === '') return $q;
        return $q->where('nama_produk', 'like', "%{$term}%");
    }
}
