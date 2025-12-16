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
        Schema::table('change_meter_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('kwh_meter_request_id')->nullable();

            $table->foreign('kwh_meter_request_id')
                ->references('id')
                ->on('kwh_meter_requests')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('change_meter_requests', function (Blueprint $table) {
            $table->dropForeign(['kwh_meter_request_id']);
            $table->dropColumn('kwh_meter_request_id');
        });
    }
};
