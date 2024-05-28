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
        Schema::create('change_meter_requests', function (Blueprint $table) {
            $table->id();
            $table->string('control_no');
            // basic information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('contact_no')->nullable();

            // address
            $table->integer('area');
            $table->integer('municipality_id');
            $table->integer('barangay_id');
            $table->integer('sitio')->nullable();

            // electric details
            $table->string('account_number');
            $table->string('care_of')->nullable();
            $table->string('feeder');
            $table->string('membership_or')->nullable();
            $table->string('membership_date')->nullable();
            $table->integer('consumer_type');

            $table->string('old_meter_no');
            $table->string('meter_or_number')->nullable();
            $table->date('meter_or_date')->nullable();

            $table->string('new_meter_no')->nullable();
            $table->string('type_of_meter');
            $table->string('last_reading')->nullable();
            $table->string('initial_reading')->nullable();
            $table->string('remarks')->nullable();
            $table->string('location')->nullable();

            // posting details
            $table->string('crew')->nullable();
            $table->time('time_acted')->nullable();
            $table->integer('status')->nullable();
            $table->string('damage_cause')->nullable();
            $table->string('crew_remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_meter_requests');
    }
};
