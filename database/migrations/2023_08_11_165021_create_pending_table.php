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
        Schema::create('pending', function (Blueprint $table) {
            $table->id();
            $table->string('transaction');
            $table->integer('table_name');
            $table->integer('table_id');
            $table->integer('sender_user_id');
            $table->integer('recipient_user_id');
            $table->integer('approval_step');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending');
    }
};
