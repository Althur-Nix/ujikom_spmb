<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftar', 'wilayah_id')) {
                $table->foreignId('wilayah_id')->nullable()->after('gelombang_id')->constrained('wilayah')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftar', 'wilayah_id')) {
                $table->dropForeign(['wilayah_id']);
                $table->dropColumn('wilayah_id');
            }
        });
    }
};