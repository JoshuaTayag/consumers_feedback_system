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
        Schema::table('kwh_meter_request_serial_numbers', function (Blueprint $table) {
            $table->boolean('action_status')->nullable()->comment('0 = acted-not completed, 1 = acted-completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kwh_meter_request_serial_numbers', function (Blueprint $table) {
            $table->dropColumn('action_status');
        });
    }
};
