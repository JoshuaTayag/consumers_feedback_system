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
        Schema::table('change_meter_contractors', function (Blueprint $table) {
            $table->unsignedBigInteger(column: 'team_leader_id')->nullable()->after('approved_by');
            $table->integer('status')->nullable()->default(0); // 0 = inactive, 1 = active
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('change_meter_contractors', function (Blueprint $table) {
            $table->dropColumn('team_leader_id');
            $table->dropColumn('status');
        });
    }
};
