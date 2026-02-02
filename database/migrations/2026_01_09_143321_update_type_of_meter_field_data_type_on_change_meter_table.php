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
            //change data type of type_of_meter field from varchar to integer
            $table->unsignedBigInteger('type_of_meter')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('change_meter_requests', function (Blueprint $table) {
            //revert data type of type_of_meter field from integer to varchar
            $table->string('type_of_meter')->change();
        });
    }
};
