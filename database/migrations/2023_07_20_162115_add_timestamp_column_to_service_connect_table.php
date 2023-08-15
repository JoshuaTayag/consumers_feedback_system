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
        if (!Schema::hasTable('Service Connect Table')) {
            Schema::table('Service Connect Table', function (Blueprint $table) {
                Schema::table('Service Connect Table', function (Blueprint $table) {
                    $table->timestamps();
                    $table->softDeletes(); 
                });
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Service Connect Table', function (Blueprint $table) {
            //
        });
    }
};
