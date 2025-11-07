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
        Schema::table('material_requisition_forms', function (Blueprint $table) {
            $table->integer('audit_by')->nullable();
            $table->timestamp('audited_date')->nullable();
            $table->integer('confirmed_by')->nullable();
            $table->timestamp('confirmed_date')->nullable();
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
