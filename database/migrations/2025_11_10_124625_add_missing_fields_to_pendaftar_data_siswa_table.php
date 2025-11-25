<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftar_data_siswa', 'nama')) {
                $table->string('nama', 120)->after('pendaftar_id');
            }
            if (!Schema::hasColumn('pendaftar_data_siswa', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['L', 'P'])->after('nama');
            }
        });
    }

    public function down()
    {
        Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
            $table->dropColumn(['nama', 'jenis_kelamin']);
        });
    }
};