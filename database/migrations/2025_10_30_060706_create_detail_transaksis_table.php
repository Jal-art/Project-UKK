<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->bigIncrements('id_detail_transaksi');

            $table->unsignedBigInteger('id_transaksi');

            // Boleh nullable, sekadar referensi ke produk
            $table->unsignedBigInteger('id_produk')->nullable();

            // ================== CATATAN PRODUK SAAT TRANSAKSI ==================
            $table->string('nama_produk')->nullable();
            $table->string('ukuran')->nullable();
            $table->string('warna')->nullable();
            $table->integer('harga_satuan')->nullable();
            // ===================================================================

            $table->integer('jumlah');
            $table->decimal('sub_total', 10, 2);
            $table->timestamps();

            // Kalau transaksi dihapus, detail ikut hilang (ini wajar)
            $table->foreign('id_transaksi')
                  ->references('id_transaksi')
                  ->on('transaksis')
                  ->onDelete('cascade');

            // Produk dihapus -> id_produk di detail jadi NULL, tapi catatan tetap ada
            // Kalau Laravel kamu gak support nullOnDelete, foreign ini boleh kamu hapus saja.
            $table->foreign('id_produk')
                  ->references('id_produk')
                  ->on('produks')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
