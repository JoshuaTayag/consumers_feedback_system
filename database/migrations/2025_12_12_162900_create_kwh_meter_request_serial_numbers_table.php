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
        Schema::create('kwh_meter_request_serial_numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meter_id');
            $table->foreign('meter_id')->references('id')->on('meters')->onDelete('cascade');
            $table->unsignedBigInteger('kwh_meter_request_id');
            $table->foreign('kwh_meter_request_id')->references('id')->on('kwh_meter_requests')->onDelete('cascade');
            $table->unsignedBigInteger('change_meter_request_id')->nullable();
            $table->foreign('change_meter_request_id')->references('id')->on('change_meter_requests')->onDelete('cascade');
            $table->integer('status')->nullable()->default(0); // 0 = Unliquidated, 1 = Liquidated;
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwh_meter_request_serial_numbers');
    }
};
