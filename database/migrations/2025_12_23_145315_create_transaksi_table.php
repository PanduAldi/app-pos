<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi');
            $table->dateTime('tanggal');
            $table->unsignedBigInteger('id_user');
            $table->integer('total');
            $table->integer('bayar');
            $table->integer('kembali');
            $table->enum('metode_pembayaran', ['cash', 'qris', 'transfer']);
            $table->timestamps();

            $table->foreign('id_user')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
