<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
            $table->dropColumn('wilayah');
            
            $table->string('province_id', 10)->nullable();
            $table->string('regency_id', 10)->nullable();
            $table->string('district_id', 10)->nullable();
            $table->string('village_id', 10)->nullable();
        });
        
        Schema::dropIfExists('wilayah');
    }

    public function down(): void
    {
        Schema::create('wilayah', function (Blueprint $table) {
            $table->id();
            $table->string('provinsi', 100)->nullable();
            $table->string('kabupaten', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('kodepos', 10)->nullable();
            $table->timestamps();
        });
        
        Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
            $table->dropColumn(['province_id', 'regency_id', 'district_id', 'village_id']);
            $table->string('wilayah', 100)->nullable();
        });
    }
};