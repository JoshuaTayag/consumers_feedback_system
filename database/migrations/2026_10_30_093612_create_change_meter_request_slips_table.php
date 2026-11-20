<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('change_meter_request_slips', function (Blueprint $table) {
      $table->id();
      // basic information
      $table->string('control_no')->unique();
      $table->string('first_name');
      $table->string('middle_name')->nullable();
      $table->string('last_name');
      $table->string('contact_no')->nullable();

      // address
      $table->unsignedBigInteger('municipality_id');
      $table->unsignedBigInteger('barangay_id');
      $table->string('sitio')->nullable();

      // electric details
      $table->string('account_number');
      $table->string('meter_no');

      // other details
      $table->string('reading')->nullable();
      $table->string('reason_for_change_meter');
      $table->string('requested_by');
      $table->string('landmark')->nullable();
      $table->unsignedBigInteger('change_meter_request_id')->nullable();
      $table->foreign('change_meter_request_id')->references('id')->on('change_meter_requests')->onDelete('cascade');
      $table->integer('status')->default(1); // 1 for pending, 2 for accepted, 3 for rejected
      $table->unsignedBigInteger('created_by');
      $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
      $table->string('rejection_remarks')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('change_meter_request_slips');
  }
};
