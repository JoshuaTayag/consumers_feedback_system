<?php

namespace App\Services;

use App\Models\ChangeMeterRequest;
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
}