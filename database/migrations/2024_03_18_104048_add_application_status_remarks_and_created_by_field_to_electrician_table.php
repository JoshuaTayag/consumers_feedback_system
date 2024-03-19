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
        Schema::table('barangay_electricians', function (Blueprint $table) {
            $table->integer('created_by')->nullable();
            $table->string('application_status_remarks')->nullable();
            $table->date('date_of_application')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangay_electricians', function (Blueprint $table) {
            //
        });
    }
};
