<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksis';
    protected $primaryKey = 'id_detail_transaksi';

    protected $fillable = [
        'id_transaksi',
        'id_produk',
        'nama_produk',
        'ukuran',
        'warna',
        'harga_satuan',
        'jumlah',
        'sub_total',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
