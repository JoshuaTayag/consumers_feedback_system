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
        if (!Schema::connection('sqlSrvMembership')->hasTable('Consumer Masterdatabase Table')) {

            Schema::connection('sqlSrvMembership')->table('Consumer Masterdatabase Table', function (Blueprint $table) {
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
        Schema::connection('sqlSrvMembership')->table('Consumer Masterdatabase Table', function (Blueprint $table) {
            $table->dropColumn('deleted_at'); 
            $table->dropColumn('created_at'); 
            $table->dropColumn('updated_at'); 
        });
    }
};
