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
        Schema::create('posted_meters_history', function (Blueprint $table) {
            $table->id();
            $table->string('sco_no');
            $table->string('old_meter_no')->nullable();
            $table->string('new_meter_no')->nullable();
            $table->string('area')->nullable();
            $table->date('process_date');
            $table->date('date_installed')->nullable();
            $table->string('action_status');
            $table->integer('posted_by');
            $table->string('feeder')->nullable();
            $table->string('leyeco_seal_no')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('erc_seal_no')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posted_meters_history');
    }
};
