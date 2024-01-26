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
        Schema::create('barangay_electrician_account_details', function (Blueprint $table) {
            $table->id();
            $table->integer('electrician_id');
            $table->string('membership_or');
            $table->date('membership_date');
            $table->string('account_no')->nullable();
            $table->string('account_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangay_electrician_account_details');
    }
};
