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
            // Drop unique constraints
            $table->dropUnique(['serial_number']);
            $table->dropUnique(['leyeco_seal_number']);
            $table->dropUnique(['erc_seal_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meters', function (Blueprint $table) {
            // Re-add unique constraints
            $table->unique('serial_number');
            $table->unique('leyeco_seal_number');
            $table->unique('erc_seal_number');
        });
    }
};