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
        if (!Schema::connection('sqlSrvMembership')->hasTable('pre_membership')) {
            Schema::connection('sqlSrvMembership')->create('pre_membership', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('middle_name');
                $table->string('last_name');
                $table->string('spouse')->nullable();
                $table->integer('joint');
                $table->integer('single');
                $table->date('date_of_birth');
                $table->string('contact_no')->nullable();
                $table->integer('district_id');
                $table->integer('municipality_id');
                $table->integer('barangay_id')->nullable();
                $table->string('sitio')->nullable();
                $table->timestamp('date_and_time_conducted');
                $table->string('pms_conductor');
                $table->string('place_conducted');
                $table->timestamps();
                $table->softDeletes(); 
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_membership');
    }
};
