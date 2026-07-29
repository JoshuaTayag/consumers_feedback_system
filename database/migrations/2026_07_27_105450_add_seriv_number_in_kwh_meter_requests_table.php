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
        Schema::table('kwh_meter_requests', function (Blueprint $table) {
            $table->string('seriv_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kwh_meter_requests', function (Blueprint $table) {
            $table->dropColumn('seriv_number');
        });
    }
};
