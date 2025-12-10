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
        Schema::create('meters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meter_type_id');
            $table->string('serial_number')->unique();
            $table->string('leyeco_seal_number')->unique();
            $table->string('erc_seal_number')->unique();
            $table->string('control_type')->nullable();
            $table->string('control_no')->nullable();
            $table->string('account_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meters');
    }
};
