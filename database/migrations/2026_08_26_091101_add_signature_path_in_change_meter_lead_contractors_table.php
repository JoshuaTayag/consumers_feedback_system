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
        Schema::table('change_meter_lead_contractors', function (Blueprint $table) {
            $table->string('signature_path')->nullable()->after('contractor_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('change_meter_lead_contractors', function (Blueprint $table) {
            $table->dropColumn('signature_path');
        });
    }
};
