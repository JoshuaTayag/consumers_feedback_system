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
        Schema::create('barangay_electrician_educational_backgrounds', function (Blueprint $table) {
            $table->id();
            $table->integer('electrician_id');
            $table->integer('educational_stage');
            $table->string('name_of_school');
            $table->string('degree_recieved')->nullable();
            $table->string('year_graduated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangay_electrician_educational_backgrounds');
    }
};
