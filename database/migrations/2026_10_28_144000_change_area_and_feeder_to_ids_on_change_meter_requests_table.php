<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Guards allow re-running after a previously failed attempt
        if (! Schema::hasColumn('change_meter_requests', 'area_id')) {
            Schema::table('change_meter_requests', function (Blueprint $table) {
                $table->unsignedBigInteger('area_id')->nullable()->after('contact_no');
            });
        }

        if (! Schema::hasColumn('change_meter_requests', 'feeder_id')) {
            Schema::table('change_meter_requests', function (Blueprint $table) {
                $table->unsignedBigInteger('feeder_id')->nullable()->after('care_of');
            });
        }

        // Legacy area was a number (1-5) and feeder a free-text name
        $areas = DB::table('areas')->pluck('id', 'name');
        foreach ($areas as $name => $id) {
            if (preg_match('/^AREA (\d+)$/i', $name, $m)) {
                DB::table('change_meter_requests')->where('area', $m[1])->update(['area_id' => $id]);
            }
        }

        // Legacy "Feeder 1-4" corresponds to new feeder "14"
        foreach (DB::table('feeders')->get(['id', 'feeder_name']) as $feeder) {
            DB::table('change_meter_requests')
                ->whereRaw("REPLACE(REPLACE(CAST([feeder] AS NVARCHAR(255)), 'Feeder ', ''), '-', '') = ?", [(string) $feeder->feeder_name])
                ->update(['feeder_id' => $feeder->id]);
        }

        // A failed earlier run may have left the area foreign key behind
        foreach (['change_meter_requests_area_id_foreign', 'change_meter_requests_feeder_id_foreign'] as $fk) {
            DB::statement("IF OBJECT_ID('{$fk}', 'F') IS NOT NULL ALTER TABLE change_meter_requests DROP CONSTRAINT {$fk}");
        }

        Schema::table('change_meter_requests', function (Blueprint $table) {
            // No cascade/set null: SQL Server rejects multiple cascade paths via areas -> feeders
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('feeder_id')->references('id')->on('feeders');
            $table->dropColumn(['area', 'feeder']);
        });
    }

    public function down(): void
    {
        Schema::table('change_meter_requests', function (Blueprint $table) {
            $table->integer('area')->nullable();
            $table->string('feeder')->nullable();
        });

        DB::table('change_meter_requests')->orderBy('id')->each(function ($row) {
            $area = $row->area_id ? DB::table('areas')->where('id', $row->area_id)->value('name') : null;
            $feeder = $row->feeder_id ? DB::table('feeders')->where('id', $row->feeder_id)->value('feeder_name') : null;

            DB::table('change_meter_requests')->where('id', $row->id)->update([
                'area' => $area ? (int) preg_replace('/\D/', '', $area) : null,
                'feeder' => $feeder,
            ]);
        });

        Schema::table('change_meter_requests', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropForeign(['feeder_id']);
            $table->dropColumn(['area_id', 'feeder_id']);
        });
    }
};
