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

    
            $table->unsignedBigInteger('id_produk')->nullable();

            $table->string('nama_produk')->nullable();
            $table->string('ukuran')->nullable();
            $table->string('warna')->nullable();
            $table->integer('harga_satuan')->nullable();

            $table->integer('jumlah');
            $table->decimal('sub_total', 10, 2);
            $table->timestamps();

            
            $table->foreign('id_transaksi')
                  ->references('id_transaksi')
                  ->on('transaksis')
                  ->onDelete('cascade');

            
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
