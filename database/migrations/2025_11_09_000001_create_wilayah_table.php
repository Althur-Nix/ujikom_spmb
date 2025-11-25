<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('wilayah')) {
            Schema::create('wilayah', function (Blueprint $table) {
                $table->id();
                $table->string('provinsi', 100)->nullable();
                $table->string('kabupaten', 100)->nullable();
                $table->string('kecamatan', 100)->nullable();
                $table->string('kelurahan', 100)->nullable();
                $table->string('kodepos', 10)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah');
    }
};