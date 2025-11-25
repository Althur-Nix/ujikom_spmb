<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pendaftar_data_ortu')) {
            Schema::create('pendaftar_data_ortu', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pendaftar_id')->constrained('pendaftar')->cascadeOnDelete();
                $table->string('nama_ayah', 120)->nullable();
                $table->string('hp_ayah', 30)->nullable();
                $table->string('pekerjaan_ayah', 100)->nullable();
                $table->string('nama_ibu', 120)->nullable();
                $table->string('hp_ibu', 30)->nullable();
                $table->string('pekerjaan_ibu', 100)->nullable();
                $table->string('wali_nama', 120)->nullable();
                $table->string('wali_hp', 30)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftar_data_ortu');
    }
};