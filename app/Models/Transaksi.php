<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_kasir',
        'tanggal',
        'total_harga',
        'uang_bayar',
        'kembalian',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'total_harga' => 'decimal:2',
        'uang_bayar'  => 'decimal:2',
        'kembalian'   => 'decimal:2',
        // created_at / updated_at otomatis Carbon
    ];

    // Kode unik tampilan: TRX-000001
    public function getKodeAttribute(): string
    {
        return 'TRX-' . str_pad((string)$this->id_transaksi, 6, '0', STR_PAD_LEFT);
    }

    public function kasir()
    {
        return $this->belongsTo(\App\Models\Kasir::class, 'id_kasir', 'id_kasir');
    }
}
