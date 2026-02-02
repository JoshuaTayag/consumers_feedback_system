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
        Schema::table('pending', function (Blueprint $table) {
            //change table_name data type to string
            $table->string('table_name')->change();
            $table->string('url')->nullable()->after('table_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pending', function (Blueprint $table) {
            //change table_name data type back to original (assuming it was something else, e.g., integer)
            $table->integer('table_name')->change();
            $table->dropColumn('url');
        });
    }
};
