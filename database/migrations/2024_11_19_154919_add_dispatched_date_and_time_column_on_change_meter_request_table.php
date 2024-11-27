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
            $table->timestamp('dispatched_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('change_meter_requests', function (Blueprint $table) {
            $table->timestamp('dispatched_date')->nullable();
        });
    }
};
