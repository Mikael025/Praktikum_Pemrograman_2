<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informasi', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->enum('category', ['penelitian', 'pengabdian', 'umum']);
            $table->enum('visibility', ['admin', 'dosen', 'semua'])->default('semua');
            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->index(['category', 'visibility', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasi');
    }
};


