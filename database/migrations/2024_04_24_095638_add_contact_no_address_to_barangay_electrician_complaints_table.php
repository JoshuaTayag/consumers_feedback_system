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
            $table->string('complainant_contact_no')->nullable();
            $table->integer('complainant_district_id');
            $table->integer('complainant_municipality_id');
            $table->integer('complainant_barangay_id');
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
