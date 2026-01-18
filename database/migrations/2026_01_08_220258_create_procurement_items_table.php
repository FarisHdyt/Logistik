<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcurementItemsTable extends Migration
{
    public function up()
    {
        Schema::create('procurement_items', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('procurement_id')->constrained('procurements')->onDelete('cascade');
            $table->foreignId('barang_id')->nullable()->constrained('barangs')->onDelete('set null');
            
            // Foreign keys tambahan dari controller
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->onDelete('set null');
            $table->foreignId('satuan_id')->nullable()->constrained('satuans')->onDelete('set null');
            $table->foreignId('gudang_id')->nullable()->constrained('gudangs')->onDelete('set null');
            
            // Informasi barang
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->string('kategori')->nullable(); // Nama kategori (denormalized)
            $table->string('satuan')->nullable(); // Nama satuan (denormalized)
            $table->string('gudang')->nullable(); // Nama gudang (denormalized)
            
            // Data pengadaan
            $table->integer('jumlah');
            $table->decimal('harga_perkiraan', 15, 2);
            $table->decimal('subtotal', 15, 2)->nullable();
            
            // Status dan tipe
            $table->enum('tipe_pengadaan', ['restock', 'baru'])->default('restock');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'completed'])->default('pending');
            
            // Informasi persetujuan/penolakan
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('alasan_penolakan')->nullable();
            
            // Informasi tambahan
            $table->text('deskripsi')->nullable();
            $table->integer('stok_minimal')->nullable();
            $table->text('keterangan')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('procurement_id');
            $table->index('barang_id');
            $table->index('kategori_id');
            $table->index('satuan_id');
            $table->index('gudang_id');
            $table->index('kode_barang');
            $table->index('status');
            $table->index('tipe_pengadaan');
            $table->index(['procurement_id', 'status']);
            $table->index('approved_at');
            $table->index('rejected_at');
            $table->index('approved_by');
            $table->index('rejected_by');
            
            // Composite index untuk pencarian
            $table->index(['kode_barang', 'nama_barang']);
            $table->index(['tipe_pengadaan', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('procurement_items');
    }
}