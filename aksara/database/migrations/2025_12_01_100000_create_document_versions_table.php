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
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->string('document_type'); // 'penelitian' or 'pengabdian'
            $table->unsignedBigInteger('document_id'); // ID of parent document
            $table->integer('version_number');
            $table->string('nama_file');
            $table->string('path_file');
            $table->text('change_notes')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('uploaded_at');
            $table->timestamp('deleted_at')->nullable(); // Soft delete for old versions
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['document_type', 'document_id']);
            $table->index('version_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_versions');
    }
};
