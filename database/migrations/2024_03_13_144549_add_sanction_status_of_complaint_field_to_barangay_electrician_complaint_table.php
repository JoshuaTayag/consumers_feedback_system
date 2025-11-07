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
        Schema::table('barangay_electrician_complaints', function (Blueprint $table) {
            // sanction
            $table->integer('sanction_type')->nullable();
            $table->date('revocation_date')->nullable();
            $table->date('date_of_suspension_from')->nullable();
            $table->date('date_of_suspension_to')->nullable();
            $table->string('sanction_remarks')->nullable();
            
            // status
            $table->integer('status_of_complaint')->nullable();
            $table->string('status_explanation')->nullable();

            $table->string('file_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangay_electrician_complaints', function (Blueprint $table) {
            //
        });
    }
};
