<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftar_data_siswa', 'wilayah')) {
                $table->string('wilayah', 100)->nullable()->after('alamat');
            }
        });
    }

    public function down()
    {
        Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
            $table->dropColumn('wilayah');
        });
    }
};