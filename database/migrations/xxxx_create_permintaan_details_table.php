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
            $table->enum('status', ['pending', 'approved', 'rejected', 'delivered'])->default('pending');
            $table->timestamps();
        });

        // TAMBAHKAN PENGECEKAN - MODIFIKASI DI SINI
        if (!Schema::hasColumn('permintaans', 'total_items')) {
            Schema::table('permintaans', function (Blueprint $table) {
                $table->integer('total_items')->default(0)->after('jumlah');
            });
        } else {
            // Jika kolom sudah ada, pastikan tipe datanya benar
            Schema::table('permintaans', function (Blueprint $table) {
                $table->integer('total_items')->default(0)->change();
            });
        }

        if (!Schema::hasColumn('permintaans', 'total_harga')) {
            Schema::table('permintaans', function (Blueprint $table) {
                $table->decimal('total_harga', 15, 2)->default(0)->after('total_items');
            });
        } else {
            Schema::table('permintaans', function (Blueprint $table) {
                $table->decimal('total_harga', 15, 2)->default(0)->change();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('permintaan_details');
        
        // Hanya drop kolom jika benar-benar ingin menghapusnya
        if (Schema::hasColumn('permintaans', 'total_items')) {
            Schema::table('permintaans', function (Blueprint $table) {
                $table->dropColumn(['total_items', 'total_harga']);
            });
        }
    }
};