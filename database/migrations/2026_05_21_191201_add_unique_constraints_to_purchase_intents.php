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
        Schema::table('purchase_intents', function (Blueprint $table) {
              $table->unique(['bom_header_id', 'bom_line_item_id'], 'unique_intent_per_line_item');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_intents', function (Blueprint $table) {
          $table->dropUnique('unique_intent_per_line_item');
        });
    }
};
