<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcurementsTable extends Migration
{
    public function up()
    {
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            
            // Identifikasi
            $table->string('kode_pengadaan')->unique();
            
            // Tipe dan prioritas
            $table->enum('tipe_pengadaan', ['baru', 'restock', 'multi'])->default('restock');
            $table->boolean('is_multi_item')->default(false);
            $table->enum('prioritas', ['normal', 'tinggi', 'mendesak'])->default('normal');
            
            // Deskripsi pengadaan
            $table->text('alasan_pengadaan');
            $table->text('catatan')->nullable();
            
            // **TAMBAHKAN KOLOM INI (PENYEBAB ERROR)**
            $table->decimal('total_perkiraan', 15, 2)->default(0);
            $table->integer('total_jumlah')->default(0);
            
            // Status
            $table->enum('status', ['pending', 'approved', 'processing', 'completed', 'cancelled', 'rejected'])->default('pending');
            
            // User yang mengajukan
            $table->foreignId('user_id')->constrained('users');
            
            // Approval info
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users');
            $table->timestamp('tanggal_disetujui')->nullable();
            $table->text('catatan_persetujuan')->nullable();
            
            // Processing info
            $table->foreignId('diproses_oleh')->nullable()->constrained('users');
            $table->timestamp('tanggal_diproses')->nullable();
            $table->text('catatan_pemrosesan')->nullable();
            
            // Completion info
            $table->foreignId('selesai_oleh')->nullable()->constrained('users');
            $table->timestamp('tanggal_selesai')->nullable();
            $table->text('catatan_penyelesaian')->nullable();
            
            // **KOLOM BARANG (untuk single-item procurement)**
            // Kolom ini hanya digunakan jika tipe_pengadaan bukan 'multi'
            $table->foreignId('barang_id')->nullable()->constrained('barangs')->onDelete('set null');
            $table->string('kode_barang')->nullable();
            $table->string('nama_barang')->nullable();
            $table->string('kategori')->nullable();
            $table->string('satuan')->nullable();
            $table->integer('jumlah')->nullable();
            $table->decimal('harga_perkiraan', 15, 2)->nullable();
            $table->integer('stok_minimal')->nullable();
            
            // Cancellation info
            $table->foreignId('dibatalkan_oleh')->nullable()->constrained('users');
            $table->text('alasan_pembatalan')->nullable();
            $table->timestamp('tanggal_dibatalkan')->nullable();
            
            // Rejection info
            $table->text('alasan_penolakan')->nullable();
            $table->timestamp('tanggal_ditolak')->nullable();
            
            // Metadata
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes untuk performa
            $table->index('kode_pengadaan');
            $table->index('status');
            $table->index('tipe_pengadaan');
            $table->index('prioritas');
            $table->index('user_id');
            $table->index('barang_id');
            $table->index(['status', 'created_at']);
            $table->index(['status', 'prioritas']);
            $table->index('created_at');
            $table->index('total_perkiraan');
            
            // Composite index untuk pencarian yang sering digunakan
            $table->index(['status', 'user_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('procurements');
    }
}