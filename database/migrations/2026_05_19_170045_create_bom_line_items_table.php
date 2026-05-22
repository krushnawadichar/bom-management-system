<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bom_line_items', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('bom_header_id')->nullable()->unsigned();
            $table->string('item_code')->nullable();
            $table->string('part_number')->nullable();
            $table->text('description');
            $table->string('part_code')->nullable();
            $table->string('material_specification')->nullable();
            $table->string('size_of_material')->nullable();

            $table->decimal('quantity', 15, 3);

            $table->string('uom')->default('NOS');

            $table->string('purchase_technical_spec_no')->nullable();
            $table->string('stock_verification')->nullable();
            $table->string('remarks')->nullable();
            $table->string('allocated_to')->nullable();

            $table->enum('inventory_status', [
                'in_stock',
                'partial',
                'out_of_stock'
            ])->nullable();

            $table->decimal('available_quantity', 15, 3)->default(0);
            $table->decimal('allocated_quantity', 15, 3)->default(0);
            $table->decimal('shortfall_quantity', 15, 3)->default(0);

            $table->integer('line_number')->nullable();

            $table->json('raw_data')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bom_line_items');
    }
};