<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ChangeMeterRequestContractor;
use Illuminate\Http\Request;
use App\Models\ChangeMeterRequest;
use App\Models\ChangeMeterRequestPostingHistory;
use App\Services\ChangeMeterService;
use App\Services\SignatureService;

class ChangeMeterApiController extends Controller
{
    protected $changeMeterService;
    protected $signatureService;
    public function __construct(ChangeMeterService $changeMeterService, SignatureService $signatureService)
    {
        $this->changeMeterService = $changeMeterService;
        $this->signatureService = $signatureService;
    }

    public function fetchChangeMeterDataPerContractor(Request $request)
    {
        $contractorId = auth()->user()->change_meter_contractor->id;

        // Fetch change meter requests for the given contractor
        $changeMeterRequests = ChangeMeterRequest::select('id', 'control_no', 'contact_no', 'sitio', 'barangay_id', 'municipality_id','account_number', 'consumer_type', 'remarks', 'old_meter_no', 'new_meter_no', 'care_of', 'location', 'meter_or_number')
            ->selectRaw("CONCAT(last_name, ', ', first_name, ' ', middle_name) as full_name")
            ->with('municipality', 'barangay')
            ->where('crew', $contractorId)
            ->where('status', '3') // Fetch only dispatched requests
            ->get()
            ->map(function ($request) {
                return [
                    'cm_id' => $request->id,
                    'control_no' => $request->control_no,
                    'full_name' => $request->full_name,
                    'contact_no' => $request->contact_no,
                    'address' => $request->sitio .', '. $request->barangay->barangay_name .', '. $request->municipality->municipality_name,
                    'consumer_type' => $request->consumer_type,
                    'meter_or' => $request->meter_or_number,
                    'remarks' => $request->remarks,
                    'land_mark' => $request->location,
                    'old_meter_no' => $request->old_meter_no,
                    'care_of' => $request->care_of,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $changeMeterRequests,
        ]);
    }
    
    public function meterPosting(Request $request)
    {
        // Basic validation first
        $request->validate([
            'cm_id' => 'required|exists:change_meter_requests,id',
            'date_acted' => 'required|date',
            'time' => 'required|date_format:H:i',
            'crew' => 'required|integer',
            'status' => 'required|integer|in:1,2', // Only allow status 1 or 2
            'care_of' => 'nullable|string',
            'last_reading' => 'nullable|numeric',
            'reading_initial' => 'nullable|numeric',
            'damage_cause' => 'nullable|string',
            'require_consumer_signature' => 'boolean',
        ]);

        // Conditional validation based on status
        if ($request->status == 2) {
            // Status 2 (acted-completed) - require meter_no, seal_no, erc_seal
            $request->validate([
                'meter_no' => 'required|string',
                'seal_no' => 'required|string',
                'erc_seal' => 'required|string',
                'crew_remarks' => 'nullable|string',

                'consumer_signature_data' => 'nullable|string', // Base64 encoded image
                'consumer_name' => 'required_with:consumer_signature_data|string|max:255',
                'consumer_position' => 'nullable|string|max:255',
                'latitude' => 'required_with:consumer_signature_data|numeric|between:-90,90',
                'longitude' => 'required_with:consumer_signature_data|numeric|between:-180,180',
                'gps_accuracy' => 'nullable|numeric',   
            ]);
        } else if ($request->status == 3) {
            // Status 3 (acted-not-completed) - these fields are optional
            $request->validate([
                'meter_no' => 'nullable|string',
                'seal_no' => 'nullable|string',
                'erc_seal' => 'nullable|string',
                'crew_remarks' => 'required|string',
            ]);
        }


        try {

            // Check if the status is already acted
            $existingRequest = ChangeMeterRequest::findOrFail($request->cm_id);
            if ($existingRequest->status == 2) { 
                return response()->json([
                    'success' => false,
                    'message' => 'This meter request has already been acted upon and cannot be posted again.'
                ], 400);
            }

            // Check if the crew assigned is the same as the logged-in user
            $loggedInCrewId = auth()->user()->change_meter_contractor->id;
            if ($existingRequest->crew != $loggedInCrewId) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to post this meter request.'
                ], 403);
            }
            
            // Validate meter number uniqueness
            if ($request->meter_no) {
                $existingMeter = \DB::table('change_meter_requests')
                    ->where('new_meter_no', $request->meter_no)
                    ->where('id', '!=', $request->cm_id) // Exclude current record
                    ->first();

                $existingPostedMeter = \DB::table('posted_meters_history')
                    ->where('new_meter_no', $request->meter_no)
                    ->first();

                if ($existingMeter || $existingPostedMeter) {
                    $control_no = $existingMeter ? $existingMeter->control_no : $existingPostedMeter->sco_no;
                    return response()->json([
                        'success' => false,
                        'message' => 'Meter number already exists',
                        'error_type' => 'meter_validation',
                        'existing_control_no' => $control_no,
                        'field' => 'meter_no'
                    ], 422);
                }
            }

            // Validate seal number uniqueness
            if ($request->seal_no) {
                $existingSeal = \DB::table('posted_meters_history')
                    ->where('leyeco_seal_no', $request->seal_no)
                    ->first();

                if ($existingSeal) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Seal number already exists',
                        'error_type' => 'seal_validation',
                        'existing_control_no' => $existingSeal->sco_no,
                        'field' => 'seal_no'
                    ], 422);
                }
            }

            // Validate ERC seal uniqueness
            if ($request->erc_seal) {
                $existingErcSeal = \DB::table('posted_meters_history')
                    ->where('erc_seal_no', $request->erc_seal)
                    ->first();

                if ($existingErcSeal) {
                    return response()->json([
                        'success' => false,
                        'message' => 'ERC seal number already exists',
                        'error_type' => 'erc_seal_validation',
                        'existing_control_no' => $existingErcSeal->sco_no,
                        'field' => 'erc_seal'
                    ], 422);
                }
            }


            \DB::beginTransaction();

            // Handle signature collection if provided
            $signatureCollected = false;
            if ($request->consumer_signature_data && $request->consumer_name) {
                $metadata = [
                    'position' => $request->consumer_position ?? 'Consumer',
                    'device_info' => $request->header('User-Agent'),
                    'collected_at' => now()->toISOString(),
                    'accuracy' => $request->gps_accuracy,
                ];

                $signatureResult = $this->signatureService->storeConsumerSignature(
                    $request->cm_id,
                    $request->consumer_signature_data,
                    $request->consumer_name,
                    $request->latitude,
                    $request->longitude,
                    $metadata
                );

                if (!$signatureResult['success']) {
                    \DB::rollback();
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to save consumer signature: ' . $signatureResult['message']
                    ], 400);
                }

                $signatureCollected = true;
            }

            // Find the existing record
            $change_meter_request = ChangeMeterRequest::findOrFail($request->cm_id);


            // Get the crew id
            $crew_id = auth()->user()->change_meter_contractor->id;
            
            // Combine date and time
            $dateTimeActed = null;
            if ($request->date_acted && $request->time) {
                $dateTimeActed = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->date_acted . ' ' . $request->time)->format('Y-m-d H:i:s');
            }

            // Prepare the data for updating
            $dataToUpdate = [
                "new_meter_no" => $request->meter_no,
                "date_time_acted" => $dateTimeActed,
                "care_of" => $request->care_of,
                "last_reading" => $request->last_reading,
                "initial_reading" => $request->reading_initial,
                "crew" => $crew_id,
                "status" => $request->status,
                "damage_cause" => $request->damage_cause,
                "crew_remarks" => $request->crew_remarks
            ];

            // Remove any null values from the update array
            $dataToUpdate = array_filter($dataToUpdate, function ($value) {
                return !is_null($value);
            });

            // Update the existing record with new data
            $change_meter_request->update($dataToUpdate);

            // Create posting history record
            ChangeMeterRequestPostingHistory::create([
                "sco_no" => $change_meter_request->control_no,
                "old_meter_no" => $change_meter_request->old_meter_no,
                "new_meter_no" => $change_meter_request->new_meter_no,
                "process_date" => date('Y-m-d', strtotime($change_meter_request->created_at)),
                "date_installed" => $request->date_acted ? date('Y-m-d H:i:s', strtotime($request->date_acted)) : null,
                "action_status" => $change_meter_request->status,
                "leyeco_seal_no" => $request->seal_no,
                "serial_no" => null,
                "area" => $change_meter_request->area,
                "feeder" => $change_meter_request->feeder,
                "erc_seal_no" => $request->erc_seal,
                "posted_by" => auth()->id(),
                "created_at" => \Carbon\Carbon::now(),
                "account_no" => $change_meter_request->account_number,
            ]);

            // Check if posting is installed (status = 2)
            if($change_meter_request->status == 2) {
                $existingRemarks = \DB::connection('sqlSrvBilling')
                    ->table('Consumers Table')
                    ->where('Accnt No', $change_meter_request->account_number)
                    ->value('Remarks') ?? '';
                
                // Remove leading and trailing spaces
                $existingRemarks = trim($existingRemarks);

                $completeRemarks = ' OM: '.$change_meter_request->old_meter_no.' DI: '.date('m/d/y', strtotime($request->date_acted));

                $newRemarks = substr($existingRemarks . $completeRemarks, 0);

                \DB::connection('sqlSrvBilling')
                    ->table('Consumers Table')
                    ->where('Accnt No', $change_meter_request->account_number)
                    ->update([
                        'Serial No' => $change_meter_request->new_meter_no,
                        'Remarks' => $newRemarks,
                    ]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Meter posting successful!',
                'data' => [
                    'control_no' => $change_meter_request->control_no,
                    'account_number' => $change_meter_request->account_number,
                    'new_meter_no' => $change_meter_request->new_meter_no,
                    'status' => $change_meter_request->status,
                    'date_time_acted' => $change_meter_request->date_time_acted,
                    'consumer_signature_collected' => $this->signatureService->hasConsumerSignature($request->cm_id)
                ]
            ], 200);

        } catch (\Exception $e) {
            // If an exception occurs during the transaction, rollback all changes
            \DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error posting meter: ' . $e->getMessage()
            ], 500);
        }
    }

    public function transferChangeMeterRequest(Request $request)
    {
        // Validation
        $request->validate([
            'cm_id' => 'required|exists:change_meter_requests,id',
            'new_crew_id' => 'required|integer'
        ]);

        // Check if the request crew is different from the logged-in user (ensure integer comparison)
        if ((int)$request->new_crew_id == (int)auth()->user()->change_meter_contractor->id) {
            return response()->json([
                'success' => false,
                'message' => 'The new crew must be different from the current crew.'
            ], 400);
        }

        // Check if the change meter crew is from the logged-in user (ensure integer comparison)
        $changeMeterRequest = ChangeMeterRequest::find($request->cm_id);
        if ((int)$changeMeterRequest->crew != (int)auth()->user()->change_meter_contractor->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to transfer this request.'
            ], 403);
        }

        $result = $this->changeMeterService->transferChangeMeterRequest(
            $request->cm_id,
            $request->new_crew_id
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => $result['data']
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 500);
        }
    }

    public function fetchChangeMeterRequestContractors(Request $request)
    {
        try {
            $contractors = ChangeMeterRequestContractor::select('id', 'first_name', 'last_name')
                ->orderBy('first_name', 'asc')
                ->whereNotNull('user_id')
                ->get()
                ->map(function ($contractor) {
                    return [
                        'id' => $contractor->id,
                        'full_name' => $contractor->first_name . ' ' . $contractor->last_name,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $contractors,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching contractors: ' . $e->getMessage()
            ], 500);
        }
    }

    public function fetchChangeMeterRequestHistory(Request $request)
    {
        try {
            $contractorId = auth()->user()->change_meter_contractor->id;

            $date_from = $request->input('date_from');
            $date_to = $request->input('date_to');
            $status = $request->input('status');

            // Fetch change meter request history for the given contractor
            $changeMeterRequests = ChangeMeterRequest::select('id', 'control_no', 'contact_no', 'sitio', 'barangay_id', 'municipality_id','account_number', 'consumer_type', 'remarks', 'old_meter_no', 'new_meter_no', 'care_of', 'location', 'meter_or_number', 'date_time_acted', 'status')
                ->selectRaw("CONCAT(last_name, ', ', first_name, ' ', middle_name) as full_name")
                ->with('municipality', 'barangay')
                ->where('crew', $contractorId)
                ->when($status !== null, function ($query) use ($status) {
                    return $query->where('status', $status);
                })
                ->orderBy('date_time_acted', 'desc')
                ->when($date_from && $date_to, function ($query) use ($date_from, $date_to) {
                    return $query->whereBetween('date_time_acted', [$date_from . ' 00:00:00', $date_to . ' 23:59:59']);
                })
                ->get()
                ->map(function ($request) {
                    return [
                        'cm_id' => $request->id,
                        'control_no' => $request->control_no,
                        'full_name' => $request->full_name,
                        'contact_no' => $request->contact_no,
                        'address' => $request->sitio .', '. $request->barangay->barangay_name .', '. $request->municipality->municipality_name,
                        'consumer_type' => $request->consumer_type,
                        'meter_or' => $request->meter_or_number,
                        'remarks' => $request->remarks,
                        'land_mark' => $request->location,
                        'old_meter_no' => $request->old_meter_no,
                        'new_meter_no' => $request->new_meter_no,
                        'date_time_acted' => $request->date_time_acted,
                        'status' => $request->status,
                        'account_no' => substr($request->account_number, 0, 2) . '-' . substr($request->account_number, 2, 4) . '-' . substr($request->account_number, 6, 4)
                    ];
                }); 
            return response()->json([
                'success' => true,
                'data' => $changeMeterRequests,
                'count_of_requests' => $changeMeterRequests->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching change meter request history: ' . $e->getMessage()
            ], 500);
        }
    }

}