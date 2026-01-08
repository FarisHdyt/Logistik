<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            
            // Data dasar pengajuan
            $table->enum('tipe_pengadaan', ['baru', 'restock']);
            $table->foreignId('barang_id')->nullable()->constrained('barangs')->onDelete('cascade');
            $table->string('kode_barang')->nullable();
            $table->string('nama_barang');
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->onDelete('set null');
            $table->foreignId('satuan_id')->nullable()->constrained('satuans')->onDelete('set null');
            $table->integer('jumlah');
            $table->decimal('harga_perkiraan', 15, 2);
            $table->enum('prioritas', ['normal', 'tinggi', 'mendesak'])->default('normal');
            $table->text('alasan_pengadaan');
            $table->text('catatan')->nullable();
            
            // Status dan tracking
            $table->enum('status', ['pending', 'approved', 'processing', 'completed', 'cancelled', 'rejected'])->default('pending');
            
            // User yang mengajukan - DITAMBAHKAN
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Persetujuan
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('tanggal_disetujui')->nullable();
            
            // Pemrosesan
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('tanggal_diproses')->nullable();
            
            // Penyelesaian
            $table->foreignId('selesai_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('tanggal_selesai')->nullable();
            
            // Pembatalan/Penolakan
            $table->foreignId('dibatalkan_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->text('alasan_pembatalan')->nullable();
            $table->timestamp('tanggal_dibatalkan')->nullable();
            $table->text('alasan_penolakan')->nullable(); // DITAMBAHKAN untuk rejected
            $table->timestamp('tanggal_ditolak')->nullable(); // DITAMBAHKAN untuk rejected
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes untuk performa query
            $table->index('tipe_pengadaan');
            $table->index('status');
            $table->index('prioritas');
            $table->index('kode_barang');
            $table->index('user_id'); // DITAMBAHKAN
            $table->index('created_at'); // DITAMBAHKAN
        });
    }

    public function down()
    {
        Schema::dropIfExists('procurements');
    }
};