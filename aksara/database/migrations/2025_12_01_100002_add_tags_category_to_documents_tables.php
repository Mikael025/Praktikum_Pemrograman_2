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
        if (! Schema::hasColumn('penelitian_documents', 'tags')) {
            Schema::table('penelitian_documents', function (Blueprint $table) {
                $table->json('tags')->nullable()->after('uploaded_at');
                $table->string('category')->nullable()->after('tags');
            });
        }

        if (! Schema::hasColumn('pengabdian_documents', 'tags')) {
            Schema::table('pengabdian_documents', function (Blueprint $table) {
                $table->json('tags')->nullable()->after('uploaded_at');
                $table->string('category')->nullable()->after('tags');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penelitian_documents', function (Blueprint $table) {
            $table->dropColumn(['tags', 'category']);
        });

        Schema::table('pengabdian_documents', function (Blueprint $table) {
            $table->dropColumn(['tags', 'category']);
        });
    }
};
