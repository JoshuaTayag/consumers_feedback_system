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
        Schema::create('service_connect_orders', function (Blueprint $table) {
            $table->id();
            $table->string('control_no');
            // basic information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('contact_no')->nullable();
            $table->string('spouse')->nullable();
            $table->string('date_of_birth')->nullable();

            // address
            $table->integer('area');
            $table->integer('district_id'); // FOR BOD REPORTS PURPOSES
            $table->integer('municipality_id');
            $table->integer('barangay_id');
            $table->string('sitio')->nullable();

            // electric details
            $table->integer('electrician_id');
            $table->integer('master_engineer');
            $table->integer('consumer_type');
            $table->integer('consumer_type');
            $table->string('account_number');
            $table->string('care_of')->nullable();
            $table->string('feeder');
            $table->string('membership_or')->nullable();
            $table->integer('consumer_type');
            $table->integer('nature_of_application');
            $table->integer('occupancy_type');
            $table->integer('line_type');
            $table->integer('consumer_class');
            $table->integer('line_phase');

            




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
            // $table->time('time_acted')->nullable();
            $table->integer('status')->nullable();
            $table->string('damage_cause')->nullable();
            $table->string('crew_remarks')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('date_time_acted')->nullable();

            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_connect_orders');
    }
};
