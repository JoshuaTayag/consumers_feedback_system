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
            $table->timestamp('liquidated_at')->nullable()->after('is_liquidated');
            $table->unsignedBigInteger('checked_by')->nullable()->after('liquidated_at');
            $table->timestamp('checked_at')->nullable()->after('checked_by');
            $table->unsignedBigInteger('approved_liquidation_by')->nullable()->after('checked_at');
            $table->timestamp('approved_liquidation_at')->nullable()->after('approved_liquidation_by');
            $table->text('liquidation_remarks')->nullable()->after('liquidated_at');
            
            // Add foreign key constraints with NO ACTION to avoid cascade conflicts
            $table->foreign('checked_by')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
            $table->foreign('approved_liquidation_by')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kwh_meter_requests', function (Blueprint $table) {
            $table->dropForeign(['checked_by']);
            $table->dropForeign(['approved_liquidation_by']);
            $table->dropColumn(['liquidated_at', 'checked_by', 'checked_at', 'approved_liquidation_by', 'liquidation_remarks']);
        });
    }
};