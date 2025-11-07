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
        Schema::create('barangay_electricians', function (Blueprint $table) {
            $table->id();
            $table->string('control_number');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('name_ext')->nullable();
            $table->string('civil_status');
            $table->string('spouse_first_name')->nullable();
            $table->string('spouse_middle_name')->nullable();
            $table->string('spouse_last_name')->nullable();
            $table->date('date_of_birth');
            $table->string('sex');
            $table->string('email_address')->nullable();
            $table->string('fb_account')->nullable();
            $table->string('valid_id_type')->nullable();
            $table->string('valid_id_number')->nullable();

            //TESDA
            $table->string('tesda_course_title')->nullable();
            $table->string('tesda_name_of_school')->nullable();
            $table->string('tesda_national_certificate_no')->nullable();
            $table->string('tesda_date_issued')->nullable();
            $table->date('tesda_valid_until_date')->nullable();

            // REGISTERED MASTER ELECTRICIAN
            $table->string('rme_license_no')->nullable();
            $table->string('rme_issued_on')->nullable();
            $table->string('rme_issued_at')->nullable();
            $table->date('rme_valid_until')->nullable();

            // REGISTERED ELECTRICAL ENGINEER
            $table->string('ree_license_no')->nullable();
            $table->string('ree_issued_on')->nullable();
            $table->string('ree_issued_at')->nullable();
            $table->date('ree_valid_until')->nullable();

            $table->integer('application_status')->nullable();
            $table->integer('application_type')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangay_electricians');
    }
};
