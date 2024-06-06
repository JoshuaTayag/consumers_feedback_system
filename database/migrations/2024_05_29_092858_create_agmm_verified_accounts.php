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
        Schema::create('agmm_verified_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_no')->unique();
            $table->string('name');
            $table->string('contact_no')->nullable();
            $table->string('membership_or')->nullable();
            $table->string('registration_type');
            $table->string('qr_code_value');
            $table->string('transpo_allowance');
            $table->string('claimed_by')->nullable();
            $table->boolean('allowance_status');
            $table->string('verified_by');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agmm_verified_accounts');
    }
};
