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
            $table->string('old_meter_no');
            $table->string('new_meter_no');
            $table->date('process_date');
            $table->date('date_installed');
            $table->string('action_status');
            $table->integer('posted_by');
            $table->timestamps();
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
