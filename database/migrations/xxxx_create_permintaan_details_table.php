<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nama', function (Blueprint $table) {
            $table->bigIncrements('id'); // kolom 1: id bigint UNSIGNED AUTO_INCREMENT
            $table->bigInteger('permintaan_id')->unsigned(); // kolom 2: permintaan_id bigint UNSIGNED
            $table->bigInteger('barang_id')->unsigned(); // kolom 3: barang_id bigint UNSIGNED
            $table->integer('jumlah'); // kolom 4: jumlah int
            $table->decimal('harga_satuan', 15, 2); // kolom 5: harga_satuan decimal(15,2)
            $table->decimal('subtotal', 15, 2); // kolom 6: subtotal decimal(15,2)
            $table->enum('status', ['pending', 'approved', 'rejected', 'delivered'])->default('pending'); // kolom 7: status enum
            $table->timestamp('created_at')->nullable(); // kolom 8: created_at timestamp
            $table->timestamp('updated_at')->nullable(); // kolom 9: updated_at timestamp
            $table->bigInteger('satker_id')->unsigned(); // kolom 10: satker_id bigint UNSIGNED
            $table->text('catatan')->nullable(); // kolom 11: catatan text

            // Foreign keys (diasumsikan berdasarkan nama kolom)
            $table->foreign('permintaan_id')->references('id')->on('permintaans')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
            $table->foreign('satker_id')->references('id')->on('satkers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('nama');
    }
};