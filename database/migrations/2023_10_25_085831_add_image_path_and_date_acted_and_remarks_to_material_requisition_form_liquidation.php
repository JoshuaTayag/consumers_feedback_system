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
        Schema::table('material_requisition_form_liquidations', function (Blueprint $table) {
            $table->string('image_path')->nullable();
            $table->timestamp('date_acted');
            $table->timestamp('date_finished');
            $table->string('lineman')->nullable();
            $table->string('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_requisition_form_liquidation', function (Blueprint $table) {
            //
        });
    }
};
