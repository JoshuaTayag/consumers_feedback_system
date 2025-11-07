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
        Schema::create('change_meter_request_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('or_no');
            $table->integer('change_meter_id');
            $table->float('total_fees');
            $table->float('total_amount_tendered');
            $table->float('change');
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_meter_request_transactions');
    }
};
