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
        Schema::create('material_requisition_forms', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->integer('district_id');
            $table->integer('municipality_id');
            $table->integer('barangay_id')->nullable();
            $table->string('sitio')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('status');
            $table->integer('requested_id');
            $table->timestamp('requested_by');
            $table->integer('approved_id');
            $table->timestamp('approved_by');
            $table->integer('processed_id');
            $table->timestamp('processed_by');
            $table->integer('released_id');
            $table->timestamp('released_by');
            $table->integer('liquidated_id');
            $table->timestamp('liquidated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_requisition_forms');
    }
};
