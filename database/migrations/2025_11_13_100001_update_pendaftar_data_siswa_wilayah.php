<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
            $table->dropForeign(['wilayah_id']);
            $table->dropColumn('wilayah_id');
            
            $table->string('province_id', 2)->nullable();
            $table->string('regency_id', 4)->nullable();
            $table->string('district_id', 7)->nullable();
            $table->string('village_id', 10)->nullable();
            
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('regency_id')->references('id')->on('regencies');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('village_id')->references('id')->on('villages');
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
            $table->dropForeign(['province_id']);
            $table->dropForeign(['regency_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['village_id']);
            
            $table->dropColumn(['province_id', 'regency_id', 'district_id', 'village_id']);
            $table->unsignedBigInteger('wilayah_id')->nullable();
            $table->foreign('wilayah_id')->references('id')->on('wilayah');
        });
    }
};