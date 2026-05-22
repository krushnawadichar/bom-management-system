<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_intents', function (Blueprint $table) {
            $table->id();
            $table->string('intent_number')->unique();
            $table->string('batch_number');
            $table->foreignId('bom_header_id')->constrained()->onDelete('cascade');
            $table->foreignId('bom_line_item_id')->constrained()->onDelete('cascade');
            $table->string('item_code')->nullable();
            $table->text('item_description');
            $table->string('material_specification')->nullable();
            $table->decimal('required_quantity', 15, 3);
            $table->decimal('available_quantity', 15, 3)->default(0);
            $table->decimal('shortfall_quantity', 15, 3);
            $table->string('priority')->default('normal');
            $table->enum('status', ['pending', 'acknowledged', 'po_raised', 'cancelled'])->default('pending');
            $table->foreignId('raised_by')->constrained('users');
            $table->foreignId('acknowledged_by')->nullable()->constrained('users');
            $table->timestamp('acknowledged_at')->nullable();
            $table->string('po_reference')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index('batch_number');
            $table->index('status');
            $table->index('intent_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_intents');
    }
};