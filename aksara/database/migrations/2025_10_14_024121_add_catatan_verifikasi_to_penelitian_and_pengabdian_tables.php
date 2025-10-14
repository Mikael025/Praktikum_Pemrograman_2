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
        Schema::table('penelitian', function (Blueprint $table) {
            $table->text('catatan_verifikasi')->nullable()->after('status');
        });

        Schema::table('pengabdian', function (Blueprint $table) {
            $table->text('catatan_verifikasi')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penelitian', function (Blueprint $table) {
            $table->dropColumn('catatan_verifikasi');
        });

        Schema::table('pengabdian', function (Blueprint $table) {
            $table->dropColumn('catatan_verifikasi');
        });
    }
};