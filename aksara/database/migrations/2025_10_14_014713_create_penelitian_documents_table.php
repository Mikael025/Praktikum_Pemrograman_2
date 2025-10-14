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
        Schema::create('penelitian_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penelitian_id')->constrained('penelitian')->cascadeOnDelete();
            $table->enum('jenis_dokumen', ['proposal', 'laporan_akhir', 'sertifikat', 'dokumen_pendukung']);
            $table->string('nama_file');
            $table->string('path_file');
            $table->timestamp('uploaded_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penelitian_documents');
    }
};
