<?php

namespace App\Services;

use App\Models\ChangeMeterRequest;
use App\Models\DataManagement\MeterType;
use App\Models\KwhMeterRequest;
use App\Models\Meter;
use App\Models\KwhMeterRequestSerialNumber;
use Illuminate\Support\Facades\DB;
use Exception;

class ChangeMeterService
{
    /**
     * Transfer change meter request to another crew
     *
     * @param int $cmId
     * @param int $newCrewId
     * @return array
     */
    public function transferChangeMeterRequest($cmId, $newCrewId)
    {
        DB::beginTransaction();
        try {
            // Find the existing record
            $changeMeterRequest = ChangeMeterRequest::findOrFail($cmId);

            // Update the crew assignment
            $changeMeterRequest->update([
                'crew' => $newCrewId,
            ]);

            DB::commit();

            // Get the name of the new crew for the message
            $newCrew = $changeMeterRequest->changeMeterRequestCrew->first_name . ' ' . $changeMeterRequest->changeMeterRequestCrew->last_name;
            
            return [
                'success' => true,
                'message' => 'Successfully Transferred to '.$newCrew,
                'data' => $changeMeterRequest
            ];
            
        } catch (Exception $e) {
            DB::rollback();
            
            return [
                'success' => false,
                'message' => 'Transfer failed: ' . $e->getMessage(),
                'error' => $e
            ];
        }
    }

    /**
     * Get meter types with calculated available count
     * 
     * @param int|null $excludeChangeMeterRequestId - Change meter request ID to exclude from pending count
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMeterTypesWithAvailability($excludeChangeMeterRequestId = null, $excludeKwhMeterRequestId = null)
    {
        $changeMeterExcludeCondition = '';
        $kwhMeterExcludeCondition = '';
        $bindings = [];
        
        // Build exclude conditions for change meter requests
        if ($excludeChangeMeterRequestId) {
            $changeMeterExcludeCondition = 'AND cmr.id != ?';
            $bindings[] = $excludeChangeMeterRequestId;
        }
        
        // Build exclude conditions for KWH meter requests
        if ($excludeKwhMeterRequestId) {
            $kwhMeterExcludeCondition = 'AND kmr.id != ?';
            $bindings[] = $excludeKwhMeterRequestId;
        }

        return MeterType::selectRaw('*, 
        (
            SELECT COALESCE(COUNT(m.id), 0) 
            FROM meters m 
            WHERE m.meter_type_id = meter_types.id 
            AND m.status = 0 
            AND m.account_number IS NULL 
            AND m.deleted_at IS NULL
        ) - (
            SELECT COALESCE(COUNT(cmr.id), 0) 
            FROM change_meter_requests cmr 
            WHERE cmr.type_of_meter = meter_types.id 
            AND cmr.new_meter_no IS NULL
            AND (cmr.status IS NULL OR cmr.status != 2)
            AND cmr.deleted_at IS NULL
            ' . $changeMeterExcludeCondition . '
        ) - (
            SELECT COALESCE(SUM(kmr.quantity - COALESCE(assigned.meter_count, 0)), 0)
            FROM kwh_meter_requests kmr
            LEFT JOIN (
                SELECT kwh_meter_request_id, COUNT(*) as meter_count 
                FROM kwh_meter_request_serial_numbers 
                WHERE deleted_at IS NULL 
                GROUP BY kwh_meter_request_id
            ) as assigned ON kmr.id = assigned.kwh_meter_request_id
            WHERE kmr.meter_code_id = meter_types.id
            AND kmr.deleted_at IS NULL
            ' . $kwhMeterExcludeCondition . '
        ) as available_count', $bindings)
        ->orderBy('meter_code', 'asc')
        ->get();

            
    }

    /**
     * Get detailed meter availability statistics for a specific meter type
     * 
     * @param int $meterTypeId - Meter type ID
     * @param int|null $excludeChangeMeterRequestId - Change meter request ID to exclude from reserved count
     * @return array
     */
    public function getMeterAvailabilityStats($meterTypeId, $excludeChangeMeterRequestId = null)
    {
        try {
            // Count total available meters (status = 0, no account, not deleted)
            $availableMeters = Meter::where('meter_type_id', $meterTypeId)
                ->where('status', 0)
                ->whereNull('account_number')
                ->whereNull('deleted_at')
                ->count();

            // Count reserved meters from change meter requests (pending/unacted requests)
            $reservedByChangeMeterQuery = ChangeMeterRequest::where('type_of_meter', $meterTypeId)
                ->where(function($query) {
                    $query->whereNull('status') // pending
                          ->orWhere('status', '!=', 2); // not completed
                })
                ->whereNull('deleted_at');

            // Exclude specific change meter request if provided (for edit scenarios)
            if ($excludeChangeMeterRequestId) {
                $reservedByChangeMeterQuery->where('id', '!=', $excludeChangeMeterRequestId);
            }

            $reservedByChangeMeter = $reservedByChangeMeterQuery->count();

            // Count reserved meters from kwh meter requests (unliquidated serials)
            $reservedByKwhMeterRequests = DB::table('kwh_meter_request_serial_numbers as krs')
                ->join('meters as m', 'krs.meter_id', '=', 'm.id')
                ->where('m.meter_type_id', $meterTypeId)
                ->where('krs.status', 0) // unliquidated
                ->whereNull('krs.change_meter_request_id') // not yet used in change meter
                ->whereNull('krs.deleted_at')
                ->count();

            // Calculate truly available meters
            $totalReserved = $reservedByChangeMeter + $reservedByKwhMeterRequests;
            $trulyAvailable = $availableMeters - $totalReserved;

            return [
                'success' => true,
                'data' => [
                    'meter_type_id' => $meterTypeId,
                    'total_available_meters' => $availableMeters,
                    'reserved_by_change_meter_requests' => $reservedByChangeMeter,
                    'reserved_by_kwh_meter_requests' => $reservedByKwhMeterRequests,
                    'total_reserved' => $totalReserved,
                    'truly_available' => max(0, $trulyAvailable), // Ensure non-negative
                    'is_available' => $trulyAvailable > 0
                ]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error calculating meter availability: ' . $e->getMessage(),
                'error' => $e
            ];
        }
    }

}