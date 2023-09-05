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
        Schema::create('material_requisition_form_liquidations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('material_requisition_form_id');
            $table->string('type');
            $table->string('type_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_requisition_form_liquidations');
    }
};
