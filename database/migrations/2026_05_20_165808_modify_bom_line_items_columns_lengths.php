<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bom_line_items', function (Blueprint $table) {
            // Increase string column lengths
            $table->string('item_code', 500)->nullable()->change();
            $table->string('part_number', 500)->nullable()->change();
            $table->text('description')->change(); // Already text, but ensure
            $table->string('part_code', 500)->nullable()->change();
            $table->string('material_specification', 500)->nullable()->change();
            $table->string('size_of_material', 500)->nullable()->change();
            $table->string('purchase_technical_spec_no', 500)->nullable()->change();
            $table->string('stock_verification', 50)->nullable()->change();
            $table->text('remarks')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bom_line_items', function (Blueprint $table) {
            $table->string('item_code', 255)->nullable()->change();
            $table->string('part_number', 255)->nullable()->change();
            $table->string('part_code', 255)->nullable()->change();
            $table->string('material_specification', 255)->nullable()->change();
            $table->string('size_of_material', 255)->nullable()->change();
            $table->string('purchase_technical_spec_no', 255)->nullable()->change();
            $table->string('stock_verification', 255)->nullable()->change();
        });
    }
};