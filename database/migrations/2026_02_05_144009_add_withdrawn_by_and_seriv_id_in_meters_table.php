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
        Schema::table('meters', function (Blueprint $table) {
            $table->unsignedBigInteger('withdrawn_by')->nullable()->after('status');
            $table->text('seriv_number')->nullable()->after('withdrawn_by');

            $table->foreign('withdrawn_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meters', function (Blueprint $table) {
            $table->dropForeign(['withdrawn_by']);
            $table->dropColumn('withdrawn_by');
            $table->dropColumn('seriv_number');
        });
    }
};
