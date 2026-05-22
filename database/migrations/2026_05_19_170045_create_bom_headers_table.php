<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bom_headers', function (Blueprint $table) {
            $table->id();
            $table->string('bom_number')->unique();
            $table->string('revision')->default('00');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('file_name')->nullable();
            $table->string('file_path');
            $table->string('original_bom_number')->nullable();
            $table->string('drawing_number')->nullable();
            $table->string('capacity')->nullable();
            $table->date('bom_date')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamp('processed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index('bom_number');
            $table->index('project_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bom_headers');
    }
};