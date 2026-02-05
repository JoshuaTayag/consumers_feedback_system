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
        Schema::create('change_meter_lead_contractors', function (Blueprint $table) {
            $table->id();
            $table->string('contractor_team_leader_full_name');
            $table->string('area')->nullable();
            $table->string('municipality')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_meter_lead_contractors');
    }
};
