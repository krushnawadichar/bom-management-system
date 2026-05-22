<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('allocation_number')->unique();
            $table->foreignId('bom_header_id')->constrained()->onDelete('cascade');
            $table->foreignId('bom_line_item_id')->constrained()->onDelete('cascade');
            $table->string('item_code');
            $table->text('item_description');
            $table->decimal('allocated_quantity', 15, 3);
            $table->decimal('original_required_quantity', 15, 3);
            $table->string('allocated_to');
            $table->string('allocated_by')->default('System');
            $table->timestamp('allocated_at');
            $table->json('allocation_details')->nullable();
            $table->timestamps();
            
            $table->index('bom_header_id');
            $table->index('item_code');
            $table->index('allocated_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_allocations');
    }
};