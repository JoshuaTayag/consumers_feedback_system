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
        Schema::create('material_requisition_form_items', function (Blueprint $table) {
            $table->id();
            $table->string('nea_code')->nullable();
            $table->integer('material_requisition_form_id');
            $table->string('item_id');
            $table->integer('quantity');
            $table->double('unit_cost', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_requisition_form_items');
    }
};
