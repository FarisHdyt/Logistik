<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('restore_history', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('format'); // sql, json
            $table->string('size'); // ukuran file
            $table->string('method'); // append, replace
            $table->integer('total_rows')->nullable();
            $table->integer('inserted_rows')->nullable();
            $table->integer('skipped_rows')->nullable();
            $table->string('status'); // success, failed
            $table->text('message')->nullable(); // pesan error/sukses
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('filename');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('restore_history');
    }
};