<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->integerIncrements('id_menu');
            $table->string('nama_menu', 50);
            $table->double('harga',10,2);
            $table->string('kategori_menu', 15);
            $table->string('status',10);
        });
        
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->integerIncrements('id_pelanggan');
            $table->string('nama', 20);
            $table->string('no_hp', 15);
            $table->string('email', 30);
            $table->date('tanggal_daftar');
            $table->double('total_transaksi', 22,2)->nullable();
            $table->double('toal_poin', 22,2)->nullable();
        });
        Schema::create('transaksi', function (Blueprint $table) {
            $table->integerIncrements('id_transaksi');
            $table->integer('id_admin');
            $table->integer('id_pelanggan');
            $table->dateTime('tanggal_taransaksi');
            $table->double('total', 10,2);
            $table->integer('poin')->nullable()->comment('point');
        });
        
        
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->integerIncrements('id_detail');
            $table->integer('id_transaksi');
            $table->integer('id_menu');
            $table->double('jumlah', 10,2);
            $table->double('subtotal', 10,2);
        });
        Schema::create('promo', function (Blueprint $table) {
            $table->integerIncrements('id_promo');
            $table->string('nama_promo', 50);
        });
        
        Schema::create('promo_detail', function (Blueprint $table) {
            $table->integerIncrements('id_promo_detail');
            $table->integer('id_promo');
            $table->double('min_trx', 22,2);
            $table->double('max_trx', 22,2);
            $table->integer('poin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo');
        Schema::dropIfExists('promo_detail');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('transaksi_detail');
        Schema::dropIfExists('pelanggan');
        Schema::dropIfExists('menu');
    }
};
