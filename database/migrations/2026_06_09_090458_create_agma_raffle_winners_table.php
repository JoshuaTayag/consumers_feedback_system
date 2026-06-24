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
        Schema::create('agma_raffle_winners', function (Blueprint $table) {
            $table->id();
            $table->string('account_no');
            $table->string('name');
            $table->string('prize');
            $table->timestamps();
            $table->index('account_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agma_raffle_winners');
    }
};
