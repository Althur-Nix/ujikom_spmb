<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pendaftar_asal_sekolah')) {
            Schema::create('pendaftar_asal_sekolah', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pendaftar_id')->constrained('pendaftar')->cascadeOnDelete();
                $table->string('npsn', 20)->nullable();
                $table->string('nama_sekolah', 150)->nullable();
                $table->string('kabupaten', 100)->nullable();
                $table->decimal('nilai_rata', 5, 2)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftar_asal_sekolah');
    }
};