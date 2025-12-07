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
        Schema::table('penelitian_documents', function (Blueprint $table) {
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])
                ->default('pending')
                ->after('category');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('verification_status');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('rejection_reason')->nullable()->after('verified_at');
            $table->integer('version')->default(1)->after('rejection_reason');
            $table->unsignedBigInteger('parent_document_id')->nullable()->after('version');
        });

        Schema::table('pengabdian_documents', function (Blueprint $table) {
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])
                ->default('pending')
                ->after('category');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('verification_status');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('rejection_reason')->nullable()->after('verified_at');
            $table->integer('version')->default(1)->after('rejection_reason');
            $table->unsignedBigInteger('parent_document_id')->nullable()->after('version');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penelitian_documents', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'verification_status',
                'verified_by',
                'verified_at',
                'rejection_reason',
                'version',
                'parent_document_id'
            ]);
        });

        Schema::table('pengabdian_documents', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'verification_status',
                'verified_by',
                'verified_at',
                'rejection_reason',
                'version',
                'parent_document_id'
            ]);
        });
    }
};
