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
            $table->timestamp('date_acted')->nullable();
            $table->timestamp('date_finished')->nullable();
            $table->string('linemans')->nullable();
            $table->string('liquidation_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_requisition_forms', function (Blueprint $table) {
            //
        });
    }
};
