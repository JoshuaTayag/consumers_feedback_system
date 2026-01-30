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
            $table->unsignedBigInteger('audited_by')->nullable()->after('approved_by');
            $table->timestamp('audited_at')->nullable()->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kwh_meter_requests', function (Blueprint $table) {
            $table->dropColumn('audited_by');
            $table->dropColumn('audited_at');
        });
    }
};
