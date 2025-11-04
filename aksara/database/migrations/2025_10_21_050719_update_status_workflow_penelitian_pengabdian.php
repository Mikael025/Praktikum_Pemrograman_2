<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, change status column to varchar to allow longer values
        Schema::table('penelitian', function (Blueprint $table) {
            $table->string('status', 50)->change();
        });
        
        Schema::table('pengabdian', function (Blueprint $table) {
            $table->string('status', 50)->change();
        });

        // Update existing data to new status values
        DB::statement("UPDATE penelitian SET status = CASE 
            WHEN status = 'draft' THEN 'diusulkan'
            WHEN status = 'menunggu_verifikasi' THEN 'diusulkan'
            WHEN status = 'terverifikasi' THEN 'lolos'
            WHEN status = 'ditolak' THEN 'tidak_lolos'
            WHEN status = 'berjalan' THEN 'lolos'
            WHEN status = 'selesai' THEN 'selesai'
            ELSE status
        END");
        
        DB::statement("UPDATE pengabdian SET status = CASE 
            WHEN status = 'draft' THEN 'diusulkan'
            WHEN status = 'menunggu_verifikasi' THEN 'diusulkan'
            WHEN status = 'terverifikasi' THEN 'lolos'
            WHEN status = 'ditolak' THEN 'tidak_lolos'
            WHEN status = 'berjalan' THEN 'lolos'
            WHEN status = 'selesai' THEN 'selesai'
            ELSE status
        END");

        // Now change back to enum with new values
        Schema::table('penelitian', function (Blueprint $table) {
            $table->enum('status', ['diusulkan', 'tidak_lolos', 'lolos_perlu_revisi', 'lolos', 'revisi_pra_final', 'selesai'])
                  ->default('diusulkan')
                  ->change();
        });
        
        Schema::table('pengabdian', function (Blueprint $table) {
            $table->enum('status', ['diusulkan', 'tidak_lolos', 'lolos_perlu_revisi', 'lolos', 'revisi_pra_final', 'selesai'])
                  ->default('diusulkan')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert penelitian table status enum
        Schema::table('penelitian', function (Blueprint $table) {
            // Update data back to old status values
            DB::statement("UPDATE penelitian SET status = CASE 
                WHEN status = 'diusulkan' THEN 'draft'
                WHEN status = 'tidak_lolos' THEN 'ditolak'
                WHEN status = 'lolos_perlu_revisi' THEN 'menunggu_verifikasi'
                WHEN status = 'lolos' THEN 'terverifikasi'
                WHEN status = 'revisi_pra_final' THEN 'terverifikasi'
                WHEN status = 'selesai' THEN 'selesai'
                ELSE status
            END");
            
            $table->dropColumn('status');
        });
        
        Schema::table('penelitian', function (Blueprint $table) {
            $table->enum('status', ['draft', 'menunggu_verifikasi', 'terverifikasi', 'ditolak', 'berjalan', 'selesai'])
                  ->default('draft')
                  ->after('sumber_dana');
        });

        // Revert pengabdian table status enum
        Schema::table('pengabdian', function (Blueprint $table) {
            // Update data back to old status values
            DB::statement("UPDATE pengabdian SET status = CASE 
                WHEN status = 'diusulkan' THEN 'draft'
                WHEN status = 'tidak_lolos' THEN 'ditolak'
                WHEN status = 'lolos_perlu_revisi' THEN 'menunggu_verifikasi'
                WHEN status = 'lolos' THEN 'terverifikasi'
                WHEN status = 'revisi_pra_final' THEN 'terverifikasi'
                WHEN status = 'selesai' THEN 'selesai'
                ELSE status
            END");
            
            $table->dropColumn('status');
        });
        
        Schema::table('pengabdian', function (Blueprint $table) {
            $table->enum('status', ['draft', 'menunggu_verifikasi', 'terverifikasi', 'ditolak', 'berjalan', 'selesai'])
                  ->default('draft')
                  ->after('sumber_dana');
        });
    }
};
