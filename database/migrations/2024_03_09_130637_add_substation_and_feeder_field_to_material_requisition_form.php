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
            $table->integer('substation_id')->nullable();
            $table->string('feeder_id')->nullable();
            $table->string('cetd_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_requisition_form', function (Blueprint $table) {
            //
        });
    }
};
