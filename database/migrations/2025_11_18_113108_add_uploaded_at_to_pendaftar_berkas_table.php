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
        Schema::table('pendaftar_berkas', function (Blueprint $table) {
            $table->timestamp('uploaded_at')->nullable()->after('is_draft');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar_berkas', function (Blueprint $table) {
            $table->dropColumn('uploaded_at');
        });
    }
};