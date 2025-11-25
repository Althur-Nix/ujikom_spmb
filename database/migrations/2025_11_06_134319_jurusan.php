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
        // Check if table doesn't exist first
        if (!Schema::hasTable('jurusan')) {
            Schema::create('jurusan', function (Blueprint $table) {
                $table->id();
                $table->string('kode', 10)->unique();
                $table->string('nama', 100);
                $table->integer('kuota')->default(0);
                $table->text('deskripsi')->nullable();
                $table->boolean('aktif')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurusan');
    }
};