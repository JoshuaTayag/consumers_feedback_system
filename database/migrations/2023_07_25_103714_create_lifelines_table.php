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
        Schema::create('lifelines', function (Blueprint $table) {
            $table->id();
            $table->string('control_no');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('maiden_name')->nullable();
            $table->string('house_no_zone_purok_sitio')->nullable();
            $table->string('street')->nullable();
            $table->integer('district_id');
            $table->integer('municipality_id');
            $table->integer('barangay_id')->nullable();
            $table->date('date_of_birth');
            $table->integer('marital_status');
            $table->string('contact_no')->nullable();
            $table->string('account_no');
            $table->integer('ownership');
            $table->string('representative_id_no')->nullable();
            $table->string('representative_full_name')->nullable();
            $table->string('pppp_id')->nullable();
            $table->string('valid_id_no');
            $table->string('swdo_certificate_no')->nullable();
            $table->string('annual_income')->nullable();
            $table->date('validity_period')->nullable();
            $table->integer('application_status')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('approved_by')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->timestamp('dis_approved_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lifelines');
    }
};
