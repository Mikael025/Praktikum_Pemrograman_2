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
        Schema::create('status_history', function (Blueprint $table) {
            $table->id();
            $table->string('statusable_type'); // Penelitian or Pengabdian
            $table->unsignedBigInteger('statusable_id');
            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50);
            $table->unsignedBigInteger('changed_by_user_id');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['statusable_type', 'statusable_id']);
            $table->foreign('changed_by_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_history');
    }
};
