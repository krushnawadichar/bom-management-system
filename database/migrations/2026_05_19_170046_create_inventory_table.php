<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->string('material_grade')->nullable();
            $table->string('uom')->default('NOS');
            $table->decimal('quantity_on_hand', 15, 3)->default(0);
            $table->decimal('quantity_reserved', 15, 3)->default(0);
            $table->decimal('minimum_stock_level', 15, 3)->default(0);
            $table->string('supplier_name')->nullable();
            $table->string('location')->nullable();
            $table->json('specifications')->nullable();
            $table->timestamps();
            
            $table->index('item_code');
            $table->index('material_grade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};