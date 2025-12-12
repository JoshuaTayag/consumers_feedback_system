<?php

namespace App\Http\Controllers;
use App\Models\Meter;
use App\Models\ChangeMeterRequest;
use App\Models\DataManagement\MeterType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use DB;

class MeterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Meter::with(['meterType', 'changeMeterRequest']);
        
        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('control_no', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('serial_number', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('leyeco_seal_number', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('erc_seal_number', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('account_number', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhereHas('meterType', function ($typeQuery) use ($searchTerm) {
                      $typeQuery->where('meter_brand', 'LIKE', '%' . $searchTerm . '%')
                                ->orWhere('meter_code', 'LIKE', '%' . $searchTerm . '%')
                                ->orWhere('meter_description', 'LIKE', '%' . $searchTerm . '%');
                  });
            });
        }
        
        // Apply status filters
        if ($request->filled('status')) {
            if ($request->status === 'available') {
                // Show only available (unassigned) meters
                $query->whereDoesntHave('changeMeterRequest')
                      ->where(function($q) {
                          $q->whereNull('control_type')
                            ->whereNull('control_no')
                            ->whereNull('account_number');
                      });
            } elseif ($request->status === 'assigned') {
                // Show only assigned meters
                $query->where(function($q) {
                    $q->whereHas('changeMeterRequest')
                      ->orWhere(function($subQ) {
                          $subQ->whereNotNull('control_type')
                               ->orWhereNotNull('control_no')
                               ->orWhereNotNull('account_number');
                      });
                });
            }
        }
        
        $meters = $query->orderBy('created_at', 'desc')->paginate(15)
                       ->appends($request->query());
        
        $meters_types = MeterType::get();
        
        return view('power_house.warehousing.data_management.meters.index', compact('meters', 'meters_types'));
    }

    public function create()
    {
        // $meter_types = MeterType::all();
        // return view('power_house.warehousing.data_management.meters.create', compact('meter_types'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meter_type_id' => 'required|integer|exists:meter_types,id',
            'serial_number' => 'required|string|max:255|unique:meters,serial_number',
            'erc_seal_number' => 'required|string|max:255|unique:meters,erc_seal_number',
            'leyeco_seal_number' => 'required|string|max:255|unique:meters,leyeco_seal_number',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Only create with the required fields
            $meter = Meter::create($request->only([
                'meter_type_id',
                'serial_number',
                'erc_seal_number',
                'leyeco_seal_number'
            ]));

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Meter created successfully',
                    'data' => $meter
                ], 201);
            }

            return redirect()->route('meters.index')->with('success', 'Meter created successfully');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create meter: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to create meter: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $meter = Meter::with('meterType')->find($id);
        
        if (!$meter) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meter not found'
                ], 404);
            }
            return redirect()->route('meters.index')->with('error', 'Meter not found');
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $meter
            ]);
        }

        $audits = $meter->audits()->with('user')->orderBy('created_at', 'desc')->get();

        return view('power_house.warehousing.data_management.meters.show', compact('meter', 'audits'));
    }

    public function edit($id)
    {
        $meter = Meter::find($id);
        $meter_types = MeterType::all();

        if (!$meter) {
            return redirect()->route('meters.index')->with('error', 'Meter not found');
        }

        return view('power_house.warehousing.data_management.meters.edit', compact('meter', 'meter_types'));
    }

    public function update(Request $request, $id)
    {
        $meter = Meter::find($id);
        
        if (!$meter) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meter not found'
                ], 404);
            }
            return redirect()->route('meters.index')->with('error', 'Meter not found');
        }

        // Check if meter is assigned - prevent editing assigned meters
        if (!empty($meter->control_type) || !empty($meter->control_no) || !empty($meter->account_number)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot edit assigned meter. Please return the meter first to make changes.'
                ], 422);
            }
            return redirect()->back()->with('error', 'Cannot edit assigned meter. Please return the meter first to make changes.');
        }

        $validator = Validator::make($request->all(), [
            'meter_type_id' => 'required|integer|exists:meter_types,id',
            'serial_number' => 'required|string|max:255|unique:meters,serial_number,' . $id,
            'erc_seal_number' => 'required|string|max:255|unique:meters,erc_seal_number,' . $id,
            'leyeco_seal_number' => 'required|string|max:255|unique:meters,leyeco_seal_number,' . $id,
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Only update the basic meter fields, not assignment fields
            $meter->update($request->only([
                'meter_type_id',
                'serial_number',
                'erc_seal_number',
                'leyeco_seal_number'
            ]));

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Meter updated successfully',
                    'data' => $meter
                ]);
            }

            return redirect()->route('meters.index')->with('success', 'Meter updated successfully');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update meter: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update meter: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $meter = Meter::find($id);
        
        if (!$meter) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meter not found'
                ], 404);
            }
            return redirect()->route('meters.index')->with('error', 'Meter not found');
        }

        try {
            DB::beginTransaction();

            $meter->delete(); // Soft delete due to SoftDeletes trait

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Meter deleted successfully'
                ]);
            }

            return redirect()->route('meters.index')->with('success', 'Meter deleted successfully');

        } catch (\Exception $e) {
            DB::rollback();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete meter: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to delete meter: ' . $e->getMessage());
        }
    }

    // API Methods
    public function validateSerialNumber(Request $request)
    {
        $serialNumber = $request->input('serial_number');
        $meterId = $request->input('meter_id'); // For updates
        
        $query = Meter::where('serial_number', $serialNumber);
        
        if ($meterId) {
            $query->where('id', '!=', $meterId);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? 'Serial number already exists' : 'Serial number is available'
        ]);
    }

    public function validateErcSeal(Request $request)
    {
        $ercSeal = $request->input('erc_seal_number');
        $meterId = $request->input('meter_id'); // For updates
        
        $query = Meter::where('erc_seal_number', $ercSeal);
        
        if ($meterId) {
            $query->where('id', '!=', $meterId);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? 'ERC seal number already exists' : 'ERC seal number is available'
        ]);
    }

    public function search(Request $request)
    {
        // Redirect to index with parameters - this maintains clean URLs
        return redirect()->route('meters.index', $request->only(['search', 'status']));
    }

    public function getAuditLogs($id)
    {
        try {
            $meter = Meter::findOrFail($id);
            $audits = $meter->audits()->with('user')->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $audits,
                'total' => $audits->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch audit logs: ' . $e->getMessage()
            ], 500);
        }
    }

    public function assign(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'control_type' => 'required|string|max:255',
                'control_no' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $meter = Meter::findOrFail($id);
            
            // Check if meter is already assigned
            if (!empty($meter->control_type) || !empty($meter->control_no) || !empty($meter->account_number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This meter is already assigned to a transaction.'
                ], 422);
            }

            // Check if control number is already assigned to another meter
            if (!empty($request->control_no)) {
                $existingMeter = Meter::where('control_no', $request->control_no)
                    ->where('id', '!=', $id)
                    ->first();
                
                if ($existingMeter) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => "Control number '{$request->control_no}' is already assigned to another meter (Serial: {$existingMeter->serial_number})."
                        ], 422);
                    }
                    return redirect()->back()->with('error', "Control number '{$request->control_no}' is already assigned to another meter (Serial: {$existingMeter->serial_number}).");
                }
            }

            DB::beginTransaction();

            $meter->update([
                'control_type' => $request->control_type,
                'control_no' => $request->control_no,
                'account_number' => $request->account_number,
            ]);

            // find change meter request by control_no and update the meter details
            if (!empty($request->control_no)) {
                $changeMeterRequest = ChangeMeterRequest::where('control_no', $request->control_no)->first();
                
                if ($changeMeterRequest) {
                    $changeMeterRequest->update([
                        'new_meter_no' => $meter->serial_number,
                    ]);
                }
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Meter assigned successfully!',
                    'data' => $meter->load('meterType')
                ]);
            }

            return redirect()->route('meters.index')->with('success', 'Meter assigned successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to assign meter: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to assign meter: ' . $e->getMessage());
        }
    }

    public function returnMeter(Request $request, $id)
    {
        try {
            $meter = Meter::findOrFail($id);
            
            // Check if meter is actually assigned
            if (empty($meter->control_type) && empty($meter->control_no) && empty($meter->account_number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This meter is not currently assigned to any transaction.'
                ], 422);
            }

            // check if the status of the corresponding change meter request is already completed
            $changeMeterRequest = ChangeMeterRequest::where('control_no', $meter->control_no)->first();
            if ($changeMeterRequest && $changeMeterRequest->status == 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot return meter. The corresponding change meter request is already completed.'
                ], 422);
            }


            DB::beginTransaction();

            // Store control_no before clearing it for ChangeMeterRequest update
            $controlNo = $meter->control_no;
            
            $meter->update([
                'control_type' => null,
                'control_no' => null,
                'account_number' => null,
            ]);

            // Clear the meter assignment in the corresponding change meter request
            if ($controlNo) {
                $changeMeterRequest = ChangeMeterRequest::where('control_no', $controlNo)->first();
                if ($changeMeterRequest) {
                    $changeMeterRequest->update([
                        'new_meter_no' => null,
                    ]);
                }
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Meter returned successfully! It is now available for new assignments.',
                    'data' => $meter->load('meterType')
                ]);
            }

            return redirect()->route('meters.index')->with('success', 'Meter returned successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to return meter: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to return meter: ' . $e->getMessage());
        }
    }

    /**
     * Get change meter requests for dropdown
     */
    public function getChangeMeterRequests(Request $request)
    {
        try {
            $changeMeterRequests = ChangeMeterRequest::where('status', null)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('meters')
                        ->whereColumn('meters.control_no', 'change_meter_requests.control_no')
                        ->whereNotNull('meters.control_no');
                })
                ->select('id', 'control_no', 'first_name', 'last_name', 'account_number')
                ->orderBy('control_no', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $changeMeterRequests->map(function ($request) {
                    return [
                        'control_no' => $request->control_no,
                        'display_text' => $request->control_no . ' - ' . $request->first_name . ' ' . $request->last_name,
                        'account_number' => $request->account_number
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch change meter requests: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate if control number is already assigned
     */
    public function validateControlNumber(Request $request)
    {
        $controlNo = $request->input('control_no');
        $meterId = $request->input('meter_id'); // The meter we're trying to assign to
        
        if (empty($controlNo)) {
            return response()->json([
                'valid' => true,
                'message' => 'Control number is empty'
            ]);
        }

        $query = Meter::where('control_no', $controlNo);
        
        if ($meterId) {
            $query->where('id', '!=', $meterId);
        }
        
        $existingMeter = $query->first();
        
        if ($existingMeter) {
            return response()->json([
                'valid' => false,
                'message' => "Control number '{$controlNo}' is already assigned to another meter (Serial: {$existingMeter->serial_number})",
                'existing_meter' => [
                    'id' => $existingMeter->id,
                    'serial_number' => $existingMeter->serial_number,
                    'control_no' => $existingMeter->control_no
                ]
            ]);
        }
        
        return response()->json([
            'valid' => true,
            'message' => 'Control number is available'
        ]);
    }
}
