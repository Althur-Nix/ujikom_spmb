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
        Schema::create('pendaftar_berkas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->cascadeOnDelete();
            $table->string('jenis')->nullable(); // IJAZAH, RAPOR, KIP, dll
            $table->string('nama_file')->nullable();
            $table->string('url')->nullable();
            $table->integer('ukuran_kb')->nullable();
            $table->boolean('valid')->default(false);
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftar_berkas');
    }
};
