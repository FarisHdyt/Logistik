<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('permintaan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_id')->constrained('permintaans')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();
        });

        // Tambah kolom untuk kompatibilitas dengan sistem lama
        Schema::table('permintaans', function (Blueprint $table) {
            $table->integer('total_items')->default(0)->after('jumlah');
            $table->decimal('total_harga', 15, 2)->default(0)->after('total_items');
        });
    }

    public function down()
    {
        Schema::dropIfExists('permintaan_details');
        
        Schema::table('permintaans', function (Blueprint $table) {
            $table->dropColumn(['total_items', 'total_harga']);
        });
    }
};