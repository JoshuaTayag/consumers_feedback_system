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
        Schema::create('electrician_activities', function (Blueprint $table) {
            $table->id();
            $table->string('activity_name');
            $table->string('facilited_by');
            $table->date('date_conducted');
            $table->time('time_conducted');
            $table->string('venue');
            $table->integer('target_participants');
            $table->integer('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electrician_activities');
    }
};
