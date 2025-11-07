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
        Schema::create('barangay_electrician_complaints', function (Blueprint $table) {
            $table->id();
            $table->string('control_number');
            $table->date('date');
            $table->string('complainant_name');
            $table->integer('electrician_id');
            $table->integer('nature_of_complaint')->nullable();
            $table->string('other_nature_of_complaint')->nullable();
            $table->integer('act_of_misconduct');
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
        Schema::dropIfExists('barangay_electrician_complaints');
    }
};
