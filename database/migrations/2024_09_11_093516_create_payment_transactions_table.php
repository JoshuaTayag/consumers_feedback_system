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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('or_no');
            $table->string('control_no');
            $table->string('first_name');
            $table->string('last_name');

            $table->integer('municipality_id');
            $table->integer('barangay_id');
            $table->string('sitio');

            $table->string('business_type_id')->nullable();
            $table->string('consumer_type_id');
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();

            $table->float('total_fees');
            $table->float('total_amount_tendered');
            $table->float('change');

            $table->timestamp('process_date');
            $table->string('processed_by');
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
