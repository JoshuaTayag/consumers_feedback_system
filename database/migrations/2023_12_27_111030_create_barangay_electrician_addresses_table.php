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
        Schema::create('barangay_electrician_addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('electrician_id');
            $table->string('house_no_sitio_purok_street')->nullable();
            $table->integer('district_id');
            $table->integer('municipality_id');
            $table->integer('barangay_id')->nullable();
            $table->integer('postal_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangay_electrician_addresses');
    }
};
