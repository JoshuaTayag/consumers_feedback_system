<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('change_meter_signatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('change_meter_request_id');
            $table->enum('signature_type', ['customer', 'contractor', 'witness'])->default('customer');
            $table->longText('signature_data'); // Base64 encoded signature image
            $table->string('signatory_name'); // Consumer name
            $table->string('signatory_position')->nullable(); // e.g., "Property Owner", "Authorized Representative"
            $table->decimal('latitude', 10, 8)->nullable(); // GPS coordinates
            $table->decimal('longitude', 11, 8)->nullable(); // GPS coordinates
            $table->timestamp('signed_at');
            $table->unsignedBigInteger('created_by'); // Contractor who collected the signature
            $table->json('metadata')->nullable(); // Additional device info, etc.
            $table->timestamps();

            $table->foreign('change_meter_request_id')
                ->references('id')
                ->on('change_meter_requests')
                ->onDelete('cascade');
            
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            
            // Prevent duplicate signatures for same type per request
            $table->unique(['change_meter_request_id', 'signature_type'], 'unique_signature_per_type');
            
            $table->index(['change_meter_request_id', 'signature_type']);
            $table->index('signed_at');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('change_meter_signatures');
    }
};