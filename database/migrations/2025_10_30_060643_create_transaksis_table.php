<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->bigIncrements('id_transaksi');
            $table->unsignedBigInteger('id_kasir');
            $table->date('tanggal');
            $table->decimal('uang_bayar', 10, 2);
            $table->decimal('kembalian', 10, 2);
            $table->decimal('total_harga', 10, 2);
            $table->timestamps();

            $table->foreign('id_kasir')
                  ->references('id_kasir')
                  ->on('kasirs')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('transaksis');
    }
};
