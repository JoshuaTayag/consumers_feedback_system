<?php

namespace App\Http\Controllers;

use App\Models\ChangeMeterLeadContractor;
use App\Models\ConsumersTable;
use App\Models\DataManagement\MeterType;
use App\Models\KwhMeterRequest;
use App\Models\KwhMeterRequestSerialNumber;
use App\Models\Meter;
use Illuminate\Http\Request;
use App\Models\ChangeMeterRequest;
use App\Models\ChangeMeterRequestContractor;
use App\Models\ChangeMeterRequestFees;
use App\Models\ChangeMeterRequestPostingHistory;
use App\Models\User;
use App\Services\ChangeMeterService;
use App\Services\SignatureService;
use DB;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ChangeMeterCompletedNotification;
use PDO;

class ChangeMeterRequestController extends Controller
{
    protected $changeMeterService;
    protected $signatureService;

    function __construct(ChangeMeterService $changeMeterService, SignatureService $signatureService)
    {
         $this->changeMeterService = $changeMeterService;
         $this->signatureService = $signatureService;
         $this->middleware('permission:change-meter-request-list|change-meter-request-create|change-meter-request-edit|change-meter-request-delete', ['only' => ['index']]);
         $this->middleware('permission:change-meter-request-create', ['only' => ['create','store']]);
         $this->middleware('permission:change-meter-request-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:change-meter-request-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cm_requests = ChangeMeterRequest::with('municipality', 'barangay', 'assignedMeter')->orderBy('id','desc')->paginate(9);
        $ref_employees = ChangeMeterRequestContractor::with('teamLeadContractor')
            ->where('status', 1)
            ->orderBy('last_name', 'ASC')
            ->get()
            ->map(function ($contractor) {
                $fullName = $contractor->last_name . ', ' . $contractor->first_name;
                if ($contractor->teamLeadContractor && $contractor->teamLeadContractor->contractor_team_leader_full_name) {
                    $fullName .= ' (' . $contractor->teamLeadContractor->contractor_team_leader_full_name . ')';
                }
                return [
                    'id' => $contractor->id,
                    'full_name' => $fullName
                ];
            });
        //     $ref_employees = DB::table('change_meter_contractors')
        // ->select(DB::raw("CONCAT(last_name, ', ', first_name) AS full_name"), 'id')
        // ->where('status', 1)
        // ->orderBy('last_name', 'ASC')
        // ->get();
        //     dd($ref_employees);
        $change_meter_status_count = $this->getStatusCountsArray();
        return view('service_connect_order.change_meter.index',compact('cm_requests', 'ref_employees', 'change_meter_status_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $municipalities = DB::connection('sqlSrvMembership')
        ->table('municipalities')
        ->select('*')
        ->orderBy('municipality_name', 'asc')
        ->get();

        $consumer_types = DB::connection('sqlSrvHousewiring')
        ->table('consumer_types')
        ->select('*')
        ->orderBy('name_type', 'asc')
        ->get();

        $occupancy_types = DB::connection('sqlSrvHousewiring')
        ->table('occupancy_types')
        ->select('*')
        ->orderBy('occupancy_name', 'asc')
        ->get();

        // Get meter types with available meter counts using service
        $type_of_meters = $this->changeMeterService->getMeterTypesWithAvailability();

        // fetch all meter requests with approved_at and have available serials
        $kwh_meter_requests = KwhMeterRequest::select('id', 'control_no', 'quantity', 'user_id')
        ->orderBy('id', 'DESC')
        ->where('approved_at', '!=', null)
        ->with(['kwhMeterRequestSerialNumbers' => function($query) {
            $query->where('status', 0)
                ->whereNull('change_meter_request_id')
                ->whereNull('deleted_at');
        }])
        ->get()
        ->filter(function($request) {
            // Only include requests that have available serials
            return $request->kwhMeterRequestSerialNumbers->count() > 0;
        })
        ->mapWithKeys(function($request) {
            $availableCount = $request->kwhMeterRequestSerialNumbers->count();
            $totalQuantity = $request->quantity;
            
            // Format: "Control No - (Available: X/Total: Y)"
            $displayText = $request->control_no .' (' . $request->user->name . ') - (Available: ' . $availableCount . '/' . $totalQuantity . ')';
            
            return [$request->id => $displayText];
        });
        
        // dd($kwh_meter_requests);
        return view('service_connect_order.change_meter.create')->with(compact( 'municipalities', 'consumer_types', 'occupancy_types', 'type_of_meters', 'kwh_meter_requests'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $change_meter_request_exists = ChangeMeterRequest::where('account_number', $request->electric_service_detail)
            ->where(function($query) {
                $query->where('status', null)
                      ->orWhere('status', 3); // dispatched to crew or pending for posting
            })
            ->whereNull('deleted_at'); // exclude archived records

        if ($change_meter_request_exists->exists()) {
            // Record exists and is not archived
            return redirect(route('indexCM'))->withWarning('This account has pending request! </br> Control No:'.$change_meter_request_exists->first('control_no')->control_no);
        }

        // validate requests
        $this->validate($request, [
            'electric_service_detail' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'area' => ['required'],
            'barangay' => ['required'],
            'municipality' => ['required'],
            'contact_no' => ['nullable', 'regex:/^((09))[0-9]{9}/', 'digits:11'],
            'membership_or' => ['required'],
            'email' => ['nullable', 'email', 'max:255'],
            // 'membership_date' => ['required'],
            // 'consumer_type' => ['required'],
            'meter_code_no' => ['required_without:kwh_meter_request_control_no'], 
            'process_date' => ['required'],
            'meter_no' => ['nullable', 'unique:sqlSrvHousewiring.Service Connect Table,MeterNo'],
            
            // Liquidation fields validation
            'kwh_meter_request_control_no' => ['nullable', 'string'],
            'meter_serial_number' => ['required_with:kwh_meter_request_control_no'],
        ]);

        // validate the meter type if the transaction is not kwh meter request liquidation
        if ($request->kwh_meter_request_control_no == null) {
            // check if the selected meter types have available meters
            $availableMeterCount = Meter::where('meter_type_id', $request->meter_code_no)
                ->where('status', 0) // available status
                ->whereNull('account_number')
                ->whereNull('deleted_at')
                ->count();

            // check for unacted change meter requests using the same meter type
            $unactedRequestsCount = ChangeMeterRequest::where('type_of_meter', $request->meter_code_no)
                ->where('status', '!=', 2) // unacted requests
                ->count();

            // Calculate truly available meters (total available - reserved by unacted requests)
            $trulyAvailableCount = $availableMeterCount - $unactedRequestsCount;

            if ($trulyAvailableCount <= 0) {
                $message = $availableMeterCount <= 0 
                    ? 'The selected meter type has no available meters.' 
                    : "The selected meter type has {$availableMeterCount} available meters, but {$unactedRequestsCount} are reserved by pending requests.";
                
                return redirect()->back()->withInput()->withErrors([
                    'meter_code_no' => $message . ' Please choose a different type or wait for pending requests to be processed.'
                ]);
            }
            
        } else {
            // get meter type from kwh meter request serial number
            $type_of_meter = KwhMeterRequest::find($request->kwh_meter_request_control_no)->pluck('meter_code_id')->first();
            $request->merge(['meter_code_no' => $type_of_meter]);
            
        }

        

        $year = date("y");

        $control_id = Helper::IDGeneratorChangeMeter(new ChangeMeterRequest, 'control_no', 5, $year, 'CM');

        DB::beginTransaction();
        try {
            // Perform the first operation (creating a record in ServiceConnectOrder)
            $change_meter_request = ChangeMeterRequest::create([
                "control_no" => $control_id,
                "first_name" => $request->first_name,
                "middle_name" => null,
                "last_name" => $request->last_name,
                "contact_no" => $request->contact_no,
                "area" => $request->area,
                "municipality_id" => $request->municipality,
                "barangay_id" => $request->barangay,
                "sitio" => $request->sitio,
                "account_number" => $request->electric_service_detail,
                "care_of" => $request->care_of,
                "feeder" => $request->feeder,
                "membership_or" => $request->membership_or,
                "consumer_type" => $request->consumer_type ? $request->consumer_type : 'N/A',
                "old_meter_no" => $request->old_meter,
                "meter_or_number" => $request->meter_or_no,
                "meter_or_date" => null,
                "new_meter_no" => $request->liquidation_meter_serial_number ? $request->liquidation_meter_serial_number : null, // ADD SERIAL NUMBER IF LIQUIDATION
                "type_of_meter" => $request->meter_code_no,
                "last_reading" => $request->last_reading,
                "initial_reading" => $request->reading_initial,
                "remarks" => $request->remarks,
                "location" => $request->location,
                "crew" => null,
                "date_time_acted" => null,
                "status" => null,
                "damage_cause" => null,
                "crew_remarks" => null,
                "created_by" => Auth::id(),
                "created_at" => Carbon::today(),
                "process_date" => $request->process_date,
                "kwh_meter_request_id" => $request->kwh_meter_request_control_no,
                "email" => $request->email,
            ]);

            // Handle liquidation details if provided
            if ($request->kwh_meter_request_control_no && $request->meter_serial_number) {
                $kwhMeterRequest = KwhMeterRequest::where('id', $request->kwh_meter_request_control_no)->first();
                $meter = Meter::find($request->meter_serial_number);
                
                if ($kwhMeterRequest && $meter) {
                    // Update the tracking record to link with this change meter request
                    $tracking = KwhMeterRequestSerialNumber::where('kwh_meter_request_id', $kwhMeterRequest->id)
                        ->where('meter_id', $meter->id)
                        ->whereNull('change_meter_request_id')
                        ->first();
                        
                    if ($tracking) {
                        $tracking->update([
                            'change_meter_request_id' => $change_meter_request->id
                        ]);
                    }

                    // assign control number and account number in meter details
                    $meter->update([
                        'control_type' => 'Change Meter',
                        'control_no' => $control_id,
                        'account_number' => $request->electric_service_detail,
                    ]);
                }
            }

            // check if there is a payment for meter accessories or calibration fee
            if ($request->meter_accessories > 0 || $request->calibration_fee > 0) {
                DB::connection('sqlSrvHousewiring')
                ->table('Transaction Table')
                ->insert([
                        "SCONo" => $control_id,
                        "Kwhm Deposit" => $request->meter_accessories,
                        "Calibration" => $request->calibration_fee,
                        "VATPercentage" => 0.12,
                        "WireUnitCost" => 29.00,
                        "MeterSeal" => 0.00,
                ]);
            }
            
            $feeFields = [
                'membership', 'energy_deposit', 'conn_fee', 'xformer_rental', 'xformer_test',
                'xformer_installation', 'xformer_removal', 'consumer_xfmr', 'consumer_pole',
                'grounding_clamp', 'grounding_rod', 'meter_seal', 'hotline_clamp',
                'meter_accessories', 'discredit_fee', 'calibration_fee', 'others',
                'housewiring_kit', 'excess_conductor', 'conductor_duplex', 'circuit_breaker'
            ];

            foreach ($feeFields as $feeField) {
                if ($request->$feeField > 0) {
                    // $vat = $request->$feeField * .12;
                    // $vatable_value = $feeField == 'meter_accessories' ? $request->$feeField + $vat : $request->$feeField;
                    ChangeMeterRequestFees::create([
                        'cm_control_no' => $change_meter_request->id,
                        'fees' => $feeField,
                        'amount' => $request->$feeField
                    ]);
                }
            }

            DB::commit();

            return redirect(route('indexCM'))->withSuccess('Record Successfully Created! </br> SCO No:'.$control_id);

        } catch (\Exception $e) {
            // If an exception occurs during the transaction, rollback all changes
            DB::rollback();
            
            // Optionally, handle the exception (log it, display an error message, etc.)
            // For example:
            // Log::error($e->getMessage());
            return response()->$e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $change_meter_request = ChangeMeterRequest::with('cmr_fees','kwhMeterRequestSerialNumbers.meter.meterType')->find($id);
        if ($change_meter_request->date_time_acted) {
            return redirect(route('indexCM'))->withWarning("Can't Update Record!");
        } else {
            $barangays = DB::connection('sqlSrvMembership')
            ->table('Barangay Table')
            ->select('*')
            ->orderBy('Brgy', 'asc')
            ->get();

            $municipalities = DB::connection('sqlSrvMembership')
            ->table('municipalities')
            ->select('*')
            ->orderBy('municipality_name', 'asc')
            ->get();

            $consumer_types = DB::connection('sqlSrvHousewiring')
            ->table('consumer_types')
            ->select('*')
            ->orderBy('name_type', 'asc')
            ->get();

            $occupancy_types = DB::connection('sqlSrvHousewiring')
            ->table('occupancy_types')
            ->select('*')
            ->orderBy('occupancy_name', 'asc')
            ->get();

            // fetch all meter requests with approved_at and have available serials
            $kwh_meter_requests = KwhMeterRequest::select('id', 'control_no', 'quantity')
            ->orderBy('id', 'DESC')
            ->where('approved_at', '!=', null)
            ->with(['kwhMeterRequestSerialNumbers' => function($query) use ($id) {
                $query->where('status', 0)
                    ->where(function($subQuery) use ($id) {
                        $subQuery->whereNull('change_meter_request_id')
                                ->orWhere('change_meter_request_id', $id);
                    })
                    ->whereNull('deleted_at');
            }])
            ->get()
            ->filter(function($request) {
                // Only include requests that have available serials
                return $request->kwhMeterRequestSerialNumbers->count() > 0;
            })
            ->mapWithKeys(function($request) {
                $availableCount = $request->kwhMeterRequestSerialNumbers->count();
                $totalQuantity = $request->quantity;
                
                // Format: "Control No - (Available: X/Total: Y)"
                $displayText = $request->control_no . ' - (Available: ' . $availableCount . '/' . $totalQuantity . ')';
                
                return [$request->id => $displayText];
            });

            // Get meter types with available meter counts using service (exclude current request)
            $type_of_meters = $this->changeMeterService->getMeterTypesWithAvailability($id, null);
            
            return view('service_connect_order.change_meter.edit')->with(compact('change_meter_request', 'barangays', 'municipalities', 'consumer_types', 'occupancy_types', 'type_of_meters', 'kwh_meter_requests'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //  dd($request);
        // Validate requests
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'area' => ['required'],
            'barangay' => ['required'],
            'municipality' => ['required'],
            'contact_no' => ['nullable', 'regex:/^((09))[0-9]{9}/', 'digits:11'],
            'membership_or' => ['required'],
            'email' => ['nullable', 'email', 'max:255'],
            // 'membership_date' => ['required'],
            'consumer_type' => ['required'],
            'meter_no' => ['nullable', 'unique:sqlSrvHousewiring.Service Connect Table,MeterNo,' . $id . ',id'], // Ensure uniqueness except for the current record
            
            'meter_code_no' => ['required_without:kwh_meter_request_control_no'], 
            'process_date' => ['required'],
            
            // Liquidation fields validation
            'kwh_meter_request_control_no' => ['nullable', 'string'],
            'meter_serial_number' => ['required_with:kwh_meter_request_control_no'],
        ]);

        $change_meter_request = ChangeMeterRequest::findOrFail($id);

        if ($request->kwh_meter_request_control_no == null && $request->meter_serial_number == null) { // if not liquidation
            // check if the selected meter types have available meters
            // dd($request->all());
            if (!$request->erc_seal && !$request->leyeco_v_seal) {
                $availableMeterCount = Meter::where('meter_type_id', $request->meter_code_no)
                    ->where('status', 0) // available status
                    ->whereNull('account_number')
                    ->whereNull('deleted_at')
                    ->count();

                // check for unacted change meter requests using the same meter type
                $unactedRequestsCount = ChangeMeterRequest::where('type_of_meter', $request->meter_code_no)
                    ->where('status', '!=', 2) // unacted requests
                    ->count();

                // Calculate truly available meters (total available - reserved by unacted requests)
                $trulyAvailableCount = $availableMeterCount - $unactedRequestsCount;

                if ($trulyAvailableCount <= 0) {
                    $message = $availableMeterCount <= 0 
                        ? 'The selected meter type has no available meters.' 
                        : "The selected meter type has {$availableMeterCount} available meters, but {$unactedRequestsCount} are reserved by pending requests.";
                    
                    return redirect()->back()->withInput()->withErrors([
                        'meter_code_no' => $message . ' Please choose a different type or wait for pending requests to be processed.'
                    ]);
                }

                // remove the change meter request id in the kwh meter request serials if previously linked
                KwhMeterRequestSerialNumber::where('change_meter_request_id', $id)->update(['change_meter_request_id' => null]);

                //remove the control number and account number in meter details if previously linked
                $control_type = ChangeMeterRequest::find($id)->kwhMeterRequest()->exists() ? 'kWh Meter Request' : null;
                ChangeMeterRequest::find($id)->assignedMeter()->update([
                    'control_type' => $control_type,
                    'control_no' => null,
                    'account_number' => null,
                    'status' => 0, // set meter status to available

                ]);
            } else {
                $request->merge(['liquidation_meter_serial_number' => $request->meter_code_no]); // if the existing meter is already assigned with a meter serial number, assign the new meter serial number to the liquidation_meter_serial_number field to avoid validation error for unique meter serial number in the service connect table
            }

            
        } else { // if liquidation
            // get meter type from kwh meter request serial number
            $kwhMeterRequest = KwhMeterRequest::find($request->kwh_meter_request_control_no);
            if (!$kwhMeterRequest) {
                return redirect()->back()->withInput()->withErrors([
                    'kwh_meter_request_control_no' => 'The selected kWh meter request does not exist.'
                ]);
            }
            $type_of_meter = $kwhMeterRequest->meter_code_id;
            $request->merge(['meter_code_no' => $type_of_meter]);
            $request->merge(['meter_or_no' => $request->meter_serial_number]); // assign meter serial number to meter OR number

            // Handle liquidation details if provided
            $kwhMeterRequest = KwhMeterRequest::where('id', $request->kwh_meter_request_control_no)->first();
            $meter = Meter::find($request->meter_serial_number);

            if ($kwhMeterRequest && $meter) {
                // Update the tracking record to link with this change meter request
                $tracking = KwhMeterRequestSerialNumber::where('kwh_meter_request_id', $kwhMeterRequest->id)
                    ->where('meter_id', $meter->id)
                    ->first();
                    
                if ($tracking) {
                    $tracking->update([
                        'change_meter_request_id' => $id
                    ]);
                }

                // if ($request->kwh_meter_request_control_no == null && $request->meter_serial_number == null && $change_meter_request->kwh_meter_request_id) {
                //     $meter->update([
                //         'control_type' => null,
                //         'control_no' => null,
                //         'account_number' => null,
                //     ]);
                // }

                // assign control number and account number in meter details
                $meter->update([
                    'control_type' => 'Change Meter',
                    'control_no' => $change_meter_request->control_no,
                    'account_number' => $change_meter_request->account_number,
                ]);
            }       
            
        }
        DB::beginTransaction();
        try {
            // Find the existing record
            $change_meter_request = ChangeMeterRequest::findOrFail($id);

            // Update the existing record with new data
            $change_meter_request->update([
                "first_name" => $request->first_name,
                "middle_name" => null,
                "last_name" => $request->last_name,
                "contact_no" => $request->contact_no,
                "area" => $request->area,
                "municipality_id" => $request->municipality,
                "barangay_id" => $request->barangay,
                "sitio" => $request->sitio,
                // "account_number" => $request->electric_service_details,
                "care_of" => $request->care_of,
                "feeder" => $request->feeder,
                "membership_or" => $request->membership_or,
                "consumer_type" => $request->consumer_type,
                "old_meter_no" => $request->old_meter,
                "meter_or_number" => $request->meter_or_no,
                "meter_or_date" => null,
                "new_meter_no" => $request->liquidation_meter_serial_number,
                "type_of_meter" => $request->meter_code_no,
                "last_reading" => $request->last_reading,
                "initial_reading" => $request->reading_initial,
                "remarks" => $request->remarks,
                "location" => $request->location,
                "crew" => null,
                "date_time_acted" => null,
                "status" => null,
                "damage_cause" => null,
                "crew_remarks" => null,
                "created_by" => Auth::id(),
                "created_at" => Carbon::today(),
                "kwh_meter_request_id" => $request->kwh_meter_request_control_no,
                "email" => $request->email,
            ]);

            // Delete existing fees and re-add them
            // ChangeMeterRequestFees::where('cm_control_no', $change_meter_request->id)->delete();

            // Fetch existing fees for this change meter request
            $existingFees = ChangeMeterRequestFees::where('cm_control_no', $change_meter_request->id)->get()->keyBy('fees');

            // Define fee fields
            $feeFields = [
                'membership', 'energy_deposit', 'conn_fee', 'xformer_rental', 'xformer_test',
                'xformer_installation', 'xformer_removal', 'consumer_xfmr', 'consumer_pole',
                'grounding_clamp', 'grounding_rod', 'meter_seal', 'hotline_clamp',
                'meter_accessories', 'discredit_fee', 'calibration_fee', 'others',
                'housewiring_kit', 'excess_conductor', 'conductor_duplex', 'circuit_breaker'
            ];

            // Loop through fee fields and create new records
            foreach ($feeFields as $feeField) {
                $newAmount = $request->$feeField;
                if ($newAmount > 0) {
                    // $vat = $newAmount * .12;
                    // $vatable_value = $feeField == 'meter_accessories' ? $newAmount + $vat : $newAmount;
                    // dd($newAmount.$feeField);
                    // dd('the membership is greater than 0'.$request->$feeField.'-'.$newAmount);
                    if ($existingFees->has($feeField)) {
                        // Update existing fee
                        $existingFee = $existingFees->get($feeField);
                        if ($existingFee->amount != $newAmount) {
                            // Update only if the amount has changed
                            $existingFee->update(['amount' => $newAmount]);
                        }
                    } else {
                        // Create a new fee if it doesn't exist
                        ChangeMeterRequestFees::create([
                            'cm_control_no' => $change_meter_request->id,
                            'fees' => $feeField,
                            'amount' => $newAmount
                        ]);
                    }
                } else {
                    // If the new amount is zero, delete the fee if it exists
                    if ($existingFees->has($feeField) && $newAmount !== null) {
                        $existingFees->get($feeField)->delete();
                    }
                }
            }

            DB::commit();

            return redirect(route('indexCM'))->withSuccess('Record Successfully Updated! </br> SCO No:' . $change_meter_request->control_no);

        } catch (\Exception $e) {
            // If an exception occurs during the transaction, rollback all changes
            DB::rollback();

            // Optionally, handle the exception (log it, display an error message, etc.)
            // For example:
            // Log::error($e->getMessage());
            return response()->$e;
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $change_meter_request = ChangeMeterRequest::find($id);

            // throw an error if there is an assign meter
            if ($change_meter_request->new_meter_no) {
                return redirect(route('indexCM'))->withWarning("Can't Archive Record with Assigned Meter!");
            }

            // remove linked change meter in kwh meter request serials if liquidation
            if ($change_meter_request->kwh_meter_request_id) {
                $change_meter_request->kwhMeterRequestSerialNumbers()
                    ->where('change_meter_request_id', $change_meter_request->id)
                    ->update(['change_meter_request_id' => null]); // unlinked the change meter request id
            }


            $change_meter_request->delete();

            $change_meter_request_fees = ChangeMeterRequestFees::where('cm_control_no', $id);
            $change_meter_request_fees->delete();

            // unliked meter details
            // if ($change_meter_request->kwh_meter_request_id) {
            //     // change kwh meter request serial status to unlinked
            //     $change_meter_request->assignedMeter()->update([
            //         'control_type' => 'kWh Meter Request', // if kwh_meter_request_id is not null then the type is kWh Meter Request
            //         'control_no' => null,
            //         'account_number' => null,
            //     ]);

            //     $change_meter_request->kwhMeterRequest->kwhMeterRequestSerialNumbers()
            //         ->where('change_meter_request_id', $change_meter_request->id)
            //         ->update(['change_meter_request_id' => null]); // unlinked the change meter request id
            // }
            
            

            DB::commit();
            
            return redirect(route('indexCM'))->withSuccess('Record Successfully Archived!');

        } catch (\Exception $e) {
            // If an exception occurs during the transaction, rollback all changes
            DB::rollback();

            // Optionally, handle the exception (log it, display an error message, etc.)
            // For example:
            // Log::error($e->getMessage());
            return response()->$e;
        }
        
    }

    public function printChangeMeterRequest(Request $request, string $id)
    {
        $change_meter_request = ChangeMeterRequest::find($id);

        $coordinates = DB::connection('sqlSrvBilling')
            ->table('Consumers Table')
            ->where('Accnt No', $change_meter_request->account_number)
            ->select('latitude', 'longitude')
            ->first(); // Fetch the coordinates as an object

        // Add coordinates to the model instance
        $change_meter_request->latitude = $coordinates->latitude ?? null;
        $change_meter_request->longitude = $coordinates->longitude ?? null;

        // Get signature data if it exists
        $signatureResponse = $this->signatureService->getSignatures($id);
        $signatures = $signatureResponse['success'] ? collect($signatureResponse['data']) : collect();
        $change_meter_request->signatures = $signatures;

        // dd($change_meter_request);
        view()->share('data', $change_meter_request);
        $pdf = PDF::loadView('service_connect_order.change_meter.print_cm_request_pdf');
        return $pdf->stream();
    }

    function validateMeterPosting(Request $request)
    {
        if($request->get('meter_no')) {
            $meter_no = $request->get('meter_no');
            $change_meter = DB::table('change_meter_requests')
                    ->where('new_meter_no', $meter_no);

            $posted_history = DB::table('posted_meters_history')
                    ->where('new_meter_no', $meter_no);
            if($change_meter->count() > 0 && $posted_history->count() > 0)
            {
            $control_no = $change_meter->first()->control_no;
            return ['not_unique', $control_no];
            }
            else
            {
            return ['unique', null];
            }
        }

        if($request->get('seal_no')) {
            $seal_no = $request->get('seal_no');
            $data = DB::table('posted_meters_history')
                    ->where('leyeco_seal_no', $seal_no);
            if($data->count() > 0)
            {
            $control_no = $data->first()->sco_no;
            return ['not_unique', $control_no];
            }
            else
            {
            return ['unique', null];
            }
        }

        if($request->get('erc_seal'))
        {
            $erc_seal = $request->get('erc_seal');
            $data = DB::table('posted_meters_history')
                    ->where('erc_seal_no', $erc_seal);
            if($data->count() > 0)
            {
            $control_no = $data->first()->sco_no;
            return ['not_unique', $control_no];
            }
            else
            {
            return ['unique', null];
            }
        }
    }

    public function meterPosting(Request $request)
    {
        // dd($request->cm_id);
        DB::beginTransaction();
        try {
            // Find the existing record
            $change_meter_request = ChangeMeterRequest::findOrFail($request->cm_id);
            
            // Combine date and time
            $dateTimeActed = null;
            if ($request->date_acted && $request->time) {
                $dateTimeActed = Carbon::createFromFormat('Y-m-d H:i', $request->date_acted . ' ' . $request->time)->format('Y-m-d H:i:s');
            }

            // Prepare the data for updating
            $dataToUpdate = [
                "new_meter_no" => $request->meter_no,
                "date_time_acted" => $dateTimeActed,
                "care_of" => $request->care_of,
                "feeder" => $request->feeder,
                "area" => $request->area,
                "last_reading" => $request->last_reading,
                "initial_reading" => $request->reading_initial,
                "crew" => $request->crew,
                "status" => $request->status,
                "damage_cause" => $request->damage_cause,
                "crew_remarks" => $request->crew_remarks
            ];

            // Remove any null values from the update array
            $dataToUpdate = array_filter($dataToUpdate, function ($value) {
                return !is_null($value);
            });

            // dd($change_meter_request->account_number);

            // Update the existing record with new data
            $change_meter_request->update($dataToUpdate);

            ChangeMeterRequestPostingHistory::create([
                "sco_no" => $change_meter_request->control_no,
                "old_meter_no" => $change_meter_request->old_meter_no,
                "new_meter_no" => $change_meter_request->new_meter_no,
                "process_date" => date('Y-m-d', strtotime($change_meter_request->created_at)),
                "date_installed" => $request->date_acted ? date('Y-m-d H:i:s', strtotime($request->date_acted)) : null,
                "action_status" => $change_meter_request->status,
                "area" => $change_meter_request->area,
                "feeder" => $change_meter_request->feeder,
                "leyeco_seal_no" => $request->seal_no,
                "serial_no" => null,
                "erc_seal_no" => $request->erc_seal,
                "posted_by" => Auth::id(),
                "created_at" => Carbon::now(),
                "account_no" => $change_meter_request->account_number,
            ]);

            // check if posting is installed
            if($change_meter_request->status == 2){

                $existingRemarks = DB::connection('sqlSrvBilling')
                ->table('Consumers Table')
                ->where('Accnt No', $change_meter_request->account_number)
                ->value('Remarks') ?? '';
                
                // Remove leading and trailing spaces
                $existingRemarks = trim($existingRemarks);

                $completeRemarks = ' OM: '.$change_meter_request->old_meter_no.' DI: '.date('m/d/y', strtotime($request->date_acted));

                $newRemarks = substr($existingRemarks . $completeRemarks, 0);

                // update Consumers Table serial no and remarks
                DB::connection('sqlSrvBilling')
                ->table('Consumers Table')
                ->where('Accnt No', $change_meter_request->account_number)
                ->update([
                    'Serial No' => $change_meter_request->new_meter_no,
                    // 'Remarks' => $newRemarks,
                ]);

                // update meter status to unavailable if the old meter is posted and existing
                $oldMeter = Meter::where('serial_number', $change_meter_request->old_meter_no)->first();
                if ($oldMeter) {
                    $oldMeter->update([
                        'status' => 2, // unavailable
                    ]);
                }

                // Send email notification if email exists
                if (!empty($change_meter_request->email)) {
                    try {
                        Notification::route('mail', $change_meter_request->email)
                            ->notify(new ChangeMeterCompletedNotification($change_meter_request));
                    } catch (\Exception $e) {
                        // Log email error but don't fail the transaction
                        \Log::error('Failed to send change meter completion email: ' . $e->getMessage());
                    }
                }

            }

            // check if this change meter request is linked to a kwh meter request for liquidation purposes
            if ($change_meter_request->kwh_meter_request_id) {
                // change kwh meter request serial status to posted
                $change_meter_request->kwhMeterRequest->kwhMeterRequestSerialNumbers()
                    ->where('change_meter_request_id', $change_meter_request->id)
                    ->update(['status' => 1]); // Assuming '1' indicates 'posted'

                // Check and update liquidation status
                // $change_meter_request->kwhMeterRequest->checkAndUpdateLiquidationStatus();
            }
            

            // dd($billing);      
            DB::commit();

            return redirect(route('indexCM'))->withSuccess('Successfully Posted!');

        } catch (\Exception $e) {
            // If an exception occurs during the transaction, rollback all changes
            DB::rollback();
            dd($e);
            // Optionally, handle the exception (log it, display an error message, etc.)
            // For example:
            // Log::error($e->getMessage());
            return response()->json(['error' => $e], 500);
        }
    }

    public function cmDispatched(Request $request){

        DB::beginTransaction();
        try {
            // Find the existing record
            $change_meter_request = ChangeMeterRequest::findOrFail($request->cm_id);

            // dd($change_meter_request);
            $change_meter_request->update([
                'status' => 3,
                'crew' => $request->crew_dispatched,
                'dispatched_date' => Carbon::now(),
            ]);
            DB::commit();
            return redirect(route('indexCM'))->withSuccess('Successfully Dispatched!');
        } catch (\Exception $e) {
            //throw $th;
            dd($e);
        }

    }

    public function cmTransferOfDispatching(Request $request){
        $result = $this->changeMeterService->transferChangeMeterRequest(
            $request->cm_id,
            $request->crew_dispatched_to
        );

        if ($result['success']) {
            return redirect(route('indexCM'))->withSuccess($result['message']);
        } else {
            return redirect(route('indexCM'))->withError($result['message']);
        }
    }

    public function search(Request $request)
    {
        $control_no = $request->input('control_no');
        $f_name = $request->input('first_name');
        $l_name = $request->input('last_name');
        $meter_no = $request->input('meter_no');
        $old_meter_no = $request->input('old_meter_no');
        $status = $request->input('status');
        // $products = Product::where('name', 'like', "%$query%")->get();
        $cm_request = ChangeMeterRequest::query();

        if ($control_no !== null && $control_no !== '') {
            $cm_request->where('control_no', 'like', "%$control_no%");
        }

        if ($f_name !== null && $f_name !== '') {
            $cm_request->where('first_name', 'like', "%$f_name%");
        }

        if ($l_name !== null && $l_name !== '') {
            $cm_request->where('last_name', 'like', "%$l_name%");
        }

        if ($meter_no !== null && $meter_no !== '') {
            $cm_request->where('new_meter_no', 'like', "%$meter_no%");
        }

        if ($old_meter_no !== null && $old_meter_no !== '') {
            $cm_request->where('old_meter_no', 'like', "%$old_meter_no%");
        }

        if ($status !== null && $status !== '') {
            if ($status == 'unacted') {
                // dd($status);
                $cm_request->whereNull('status');
            }
            if ($status == 'dispatched') {
                // dd($status);
                $cm_request->where('status', 3);
            }
            if ($status == 'acted_not_completed') {
                // dd($status);
                $cm_request->where('status', 1);
            }
            if ($status == 'acted_completed') {
                // dd($status);
                $cm_request->where('status', 2);
            }
        }
        $cm_requests = $cm_request->orderBy('control_no','DESC')->paginate(9);

        $ref_employees = ChangeMeterRequestContractor::with('teamLeadContractor')
            ->where('status', 1)
            ->orderBy('last_name', 'ASC')
            ->get()
            ->map(function ($contractor) {
                $fullName = $contractor->last_name . ', ' . $contractor->first_name;
                if ($contractor->teamLeadContractor && $contractor->teamLeadContractor->contractor_team_leader_full_name) {
                    $fullName .= ' (' . $contractor->teamLeadContractor->contractor_team_leader_full_name . ')';
                }
                return [
                    'id' => $contractor->id,
                    'full_name' => $fullName
                ];
            });

        $change_meter_status_count = $this->getStatusCountsArray();

        // return view('products.index', compact('products'));
        return view('service_connect_order.change_meter.index',compact('cm_requests','ref_employees','change_meter_status_count'));
    }

    public function view(string $id)
    {
        $cm_request = ChangeMeterRequest::find($id);
        
        // Get signature data if it exists
        $signatureResponse = $this->signatureService->getSignatures($id);
        $signatures = $signatureResponse['success'] ? collect($signatureResponse['data']) : collect();

        // Get audit logs for this change meter request
        $audits = $cm_request->audits()->with('user')->orderBy('created_at', 'desc')->get();
        
        return view('service_connect_order.change_meter.view_acted_request', compact('cm_request', 'signatures', 'audits'));
    }

    public function viewReport(Request $request)
    {
        $municipalities = DB::connection('sqlSrvMembership')
        ->table('municipalities')
        ->select('*')
        ->orderBy('municipality_name', 'asc')
        ->get();

        $contractors = ChangeMeterLeadContractor::pluck('contractor_team_leader_full_name', 'id');
        // dd($contractors);
        return view('service_connect_order.change_meter.report', compact('municipalities', 'contractors'));
    }

    public function generateReport(Request $request)
    {
        
        // Start building the query
        $query = ChangeMeterRequest::whereBetween('created_at', [$request->date_from, $request->date_to]);

        // Add the app_status condition if it is set to 1
        if ($request->app_status == 1) {
            $query->whereNull('status'); // unacted
        }

        if ($request->app_status == 2) {
            $query->where('status', 2); // acted - completed
        }

        if ($request->app_status == 3) {
            $query->where('status', 1); // acted - not completed
        }

        if ($request->app_status == 4) {
            $query->where('status', 3); // DISPATCHED
        }

        if ($request->area) {
            $query->where('area', $request->area);
        }

        if ($request->municipality) {
            $query->where('municipality_id', $request->municipality);
        }

        if ($request->barangay) {
            $query->where('barangay_id', $request->barangay);
        }

        if ($request->contractor_id) {
            // get all crew of this contractor
            $crews = ChangeMeterRequestContractor::where('team_leader_id', $request->contractor_id)->pluck('id')->toArray();
            $query->whereIn('crew', $crews);
        }

        // Execute the query and get the results
        $change_meter_requests = $query->get();
        
        // Get all request IDs
        $requestIds = $change_meter_requests->pluck('id')->toArray();
        
        // Get all signatures at once (this depends on your SignatureService implementation)
        $allSignatures = [];
        if (!empty($requestIds)) {
            // You might need to modify your SignatureService to support batch retrieval
            foreach ($requestIds as $requestId) {
                $signatureResponse = $this->signatureService->getSignatures($requestId);
                if ($signatureResponse['success']) {
                    $allSignatures[$requestId] = collect($signatureResponse['data']);
                }
            }
        }

        view()->share('datas', $change_meter_requests);
        view()->share('signatures', $allSignatures);
        $pdf = PDF::loadView('service_connect_order.change_meter.pdf_reports')->setPaper('legal', 'landscape');
        return $pdf->stream();
    }

    public function getAccountDetails(Request $request){
        $search = $request->search;

        // Query Consumers Table with subquery to exclude blocked accounts
        $accounts = DB::table('Consumers Table')
            ->whereNotIn('Accnt No', function($query) {
                $query->select('account_number')
                    ->from('change_meter_requests')
                    ->where(function($q) {
                        $q->whereNull('status')
                          ->orWhere('status', 0)
                          ->orWhere('status', 3);
                    })
                    ->whereNull('deleted_at')
                    ->distinct();
            });

        if($search !== ''){
            $accounts->where('Accnt No', 'like', '%' . $search . '%');
        }

        $accounts->select('Accnt No as id', 'Name', 'Address', 'OR No', 'Date', 'Prev Reading', 'Serial No', 'Cons Type');

        $data = $accounts->paginate(10, ['*'], 'page', $request->page);
        return response()->json($data); 
    }

    /**
     * Get audit logs for a specific change meter request
     */
    public function getAuditLogs(string $id)
    {
        try {
            $cm_request = ChangeMeterRequest::findOrFail($id);
            $audits = $cm_request->audits()->with('user')->orderBy('created_at', 'desc')->get();
            
            $formattedAudits = $audits->map(function ($audit) {
                $changes = [];
                $createdData = [];
                
                if ($audit->event == 'created' && $audit->new_values) {
                    // For created events, show important initial values
                    $importantFields = [
                        'control_no', 'first_name', 'last_name', 'account_number', 
                        'area', 'municipality_id', 'barangay_id', 'old_meter_no', 
                        'type_of_meter', 'created_by'
                    ];
                    
                    foreach ($importantFields as $field) {
                        if (array_key_exists($field, $audit->new_values)) {
                            $createdData[] = [
                                'field' => ucwords(str_replace('_', ' ', $field)),
                                'value' => is_null($audit->new_values[$field]) ? 'NULL' : $audit->new_values[$field]
                            ];
                        }
                    }
                    
                    // If no important fields found, show first 8 fields
                    if (empty($createdData)) {
                        $count = 0;
                        foreach ($audit->new_values as $key => $value) {
                            if ($count >= 8) break;
                            $createdData[] = [
                                'field' => ucwords(str_replace('_', ' ', $key)),
                                'value' => is_null($value) ? 'NULL' : $value
                            ];
                            $count++;
                        }
                    }
                }
                
                if ($audit->event == 'updated' && $audit->old_values && $audit->new_values) {
                    foreach ($audit->new_values as $key => $newValue) {
                        if (array_key_exists($key, $audit->old_values) && $audit->old_values[$key] != $newValue) {
                            $changes[] = [
                                'field' => ucwords(str_replace('_', ' ', $key)),
                                'old_value' => is_null($audit->old_values[$key]) ? 'NULL' : $audit->old_values[$key],
                                'new_value' => is_null($newValue) ? 'NULL' : $newValue
                            ];
                        }
                    }
                }
                
                return [
                    'id' => $audit->id,
                    'event' => $audit->event,
                    'user_name' => $audit->user ? $audit->user->name : 'System',
                    'created_at' => $audit->created_at->format('M d, Y h:i A'),
                    'changes' => $changes,
                    'created_data' => $createdData,
                    'ip_address' => $audit->ip_address,
                    'user_agent' => $audit->user_agent
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $formattedAudits,
                'total' => $audits->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch audit logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export audit logs for a specific change meter request to CSV
     */
    public function exportAuditLogs(string $id)
    {
        try {
            $cm_request = ChangeMeterRequest::findOrFail($id);
            $audits = $cm_request->audits()->with('user')->orderBy('created_at', 'desc')->get();
            
            $filename = 'change_meter_audit_logs_' . $cm_request->control_no . '_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($audits, $cm_request) {
                $file = fopen('php://output', 'w');
                
                // Add CSV headers
                fputcsv($file, [
                    'Control No', 'Date & Time', 'User', 'Event', 'Field Changed', 
                    'Old Value', 'New Value', 'IP Address', 'User Agent'
                ]);
                
                foreach ($audits as $audit) {
                    if ($audit->event == 'created' && $audit->new_values) {
                        // For created events, export important initial values
                        $importantFields = [
                            'control_no', 'first_name', 'last_name', 'account_number', 
                            'area', 'old_meter_no', 'type_of_meter'
                        ];
                        
                        foreach ($importantFields as $field) {
                            if (array_key_exists($field, $audit->new_values)) {
                                fputcsv($file, [
                                    $cm_request->control_no,
                                    $audit->created_at->format('M d, Y h:i A'),
                                    $audit->user ? $audit->user->name : 'System',
                                    ucfirst($audit->event),
                                    ucwords(str_replace('_', ' ', $field)),
                                    '-',
                                    is_null($audit->new_values[$field]) ? 'NULL' : $audit->new_values[$field],
                                    $audit->ip_address,
                                    $audit->user_agent
                                ]);
                            }
                        }
                    } elseif ($audit->event == 'updated' && $audit->old_values && $audit->new_values) {
                        foreach ($audit->new_values as $key => $newValue) {
                            if (array_key_exists($key, $audit->old_values) && $audit->old_values[$key] != $newValue) {
                                fputcsv($file, [
                                    $cm_request->control_no,
                                    $audit->created_at->format('M d, Y h:i A'),
                                    $audit->user ? $audit->user->name : 'System',
                                    ucfirst($audit->event),
                                    ucwords(str_replace('_', ' ', $key)),
                                    is_null($audit->old_values[$key]) ? 'NULL' : $audit->old_values[$key],
                                    is_null($newValue) ? 'NULL' : $newValue,
                                    $audit->ip_address,
                                    $audit->user_agent
                                ]);
                            }
                        }
                    } elseif ($audit->event == 'deleted' && $audit->old_values) {
                        // For deleted events, show key fields that were deleted
                        $keyFields = ['control_no', 'first_name', 'last_name', 'account_number', 'status'];
                        foreach ($keyFields as $field) {
                            if (array_key_exists($field, $audit->old_values)) {
                                fputcsv($file, [
                                    $cm_request->control_no,
                                    $audit->created_at->format('M d, Y h:i A'),
                                    $audit->user ? $audit->user->name : 'System',
                                    ucfirst($audit->event),
                                    ucwords(str_replace('_', ' ', $field)),
                                    is_null($audit->old_values[$field]) ? 'NULL' : $audit->old_values[$field],
                                    '-',
                                    $audit->ip_address,
                                    $audit->user_agent
                                ]);
                            }
                        }
                    } else {
                        // For other events or events without detailed data
                        fputcsv($file, [
                            $cm_request->control_no,
                            $audit->created_at->format('M d, Y h:i A'),
                            $audit->user ? $audit->user->name : 'System',
                            ucfirst($audit->event),
                            $audit->event == 'created' ? 'Record Created' : 'Record ' . ucfirst($audit->event),
                            '-',
                            '-',
                            $audit->ip_address,
                            $audit->user_agent
                        ]);
                    }
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to export audit logs: ' . $e->getMessage());
        }
    }

    /**
     * Get KWH meter request details for auto-fill
     */
    public function getKwhMeterRequestDetails(Request $request)
    {
        try {
            $controlNo = $request->get('control_no');

            $kwhMeterRequest = KwhMeterRequest::with(['user', 'meterType'])
                ->where('id', $controlNo)
                ->first();
            
            if (!$kwhMeterRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'KWH meter request not found'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'requested_by' => $kwhMeterRequest->user->name,
                    'meter_type' => $kwhMeterRequest->meterType ? $kwhMeterRequest->meterType->meter_brand.' - '.$kwhMeterRequest->meterType->meter_code : 'N/A',
                    'quantity' => $kwhMeterRequest->quantity,
                    'remaining' => $kwhMeterRequest->remaining_quantity
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching KWH meter request details: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get available meter serial numbers for a KWH meter request
     */
    public function getKwhMeterSerialNumbers(Request $request)
    {
        
        try {
            $controlNo = $request->get('control_no');
            $changeMeterRequestId = $request->get('change_meter_request_id'); // Get the change meter request ID if editing
            
            $kwhMeterRequest = KwhMeterRequest::where('id', $controlNo)->first();
            
            if (!$kwhMeterRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'KWH meter request not found'
                ]);
            }
            
            // Get meters assigned to this KWH meter request
            $assignedMeters = Meter::join('kwh_meter_request_serial_numbers', 'meters.id', '=', 'kwh_meter_request_serial_numbers.meter_id')
                ->where('kwh_meter_request_serial_numbers.kwh_meter_request_id', $kwhMeterRequest->id)
                ->where('kwh_meter_request_serial_numbers.status', 0) // Unliquidated
                ->whereNull('kwh_meter_request_serial_numbers.deleted_at')
                ->where(function($query) use ($changeMeterRequestId) {
                    $query->whereNull('kwh_meter_request_serial_numbers.change_meter_request_id');
                    if ($changeMeterRequestId) {
                        $query->orWhere('kwh_meter_request_serial_numbers.change_meter_request_id', $changeMeterRequestId);
                    }
                })
                ->select('meters.id', 'meters.serial_number', 'meters.erc_seal_number', 'meters.leyeco_seal_number')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $assignedMeters
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching meter serial numbers: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get meter seal details by meter ID
     */
    public function getMeterSealDetails(Request $request)
    {
        try {
            $meterId = $request->get('meter_id');
            
            $meter = Meter::find($meterId);
            
            if (!$meter) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meter not found'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'serial_number' => $meter->serial_number,
                    'erc_seal' => $meter->erc_seal_number,
                    'leyeco_seal' => $meter->leyeco_seal_number
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching meter seal details: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Private helper method to get status counts as array (for internal use)
     */
    private function getStatusCountsArray()
    {
        $today_count = [
            'unacted' => ChangeMeterRequest::whereNull('status')
                ->whereDate('created_at', '=', Carbon::today()->toDateString())
                ->count() ?? 0,
            'acted_completed' => ChangeMeterRequest::where('status', 2)
                ->whereDate('created_at', '=', Carbon::today()->toDateString())
                ->count() ?? 0,
            'acted_not_completed' => ChangeMeterRequest::where('status', 1)
                ->whereDate('created_at', '=', Carbon::today()->toDateString())
                ->count() ?? 0,
            'dispatched' => ChangeMeterRequest::where('status', 3)
                ->whereDate('created_at', '=', Carbon::today()->toDateString())
                ->count() ?? 0,
        ];

        $yesterday_count = [
            'unacted' => ChangeMeterRequest::whereNull('status')
                ->whereDate('created_at', '=', Carbon::yesterday()->toDateString())
                ->count() ?? 0,
            'acted_completed' => ChangeMeterRequest::where('status', 2)
                ->whereDate('created_at', '=', Carbon::yesterday()->toDateString())
                ->count() ?? 0,
            'acted_not_completed' => ChangeMeterRequest::where('status', 1)
                ->whereDate('created_at', '=', Carbon::yesterday()->toDateString())
                ->count() ?? 0,
            'dispatched' => ChangeMeterRequest::where('status', 3)
                ->whereDate('created_at', '=', Carbon::yesterday()->toDateString())
                ->count() ?? 0,
        ];

        $old_transaction_count = [
            'unacted' => ChangeMeterRequest::whereNull('status')
                ->whereDate('created_at', '<', Carbon::yesterday()->toDateString())
                ->count() ?? 0,
            'acted_completed' => ChangeMeterRequest::where('status', 2)
                ->whereDate('created_at', '<', Carbon::yesterday()->toDateString())
                ->count() ?? 0,
            'acted_not_completed' => ChangeMeterRequest::where('status', 1)
                ->whereDate('created_at', '<', Carbon::yesterday()->toDateString())
                ->count() ?? 0,
            'dispatched' => ChangeMeterRequest::where('status', 3)
                ->whereDate('created_at', '<', Carbon::yesterday()->toDateString())
                ->count() ?? 0,
        ];

        $total_count = [
            'unacted' => ChangeMeterRequest::whereNull('status')->count() ?? 0,
            'acted_completed' => ChangeMeterRequest::where('status', 2)->count() ?? 0,
            'acted_not_completed' => ChangeMeterRequest::where('status', 1)->count() ?? 0,
            'dispatched' => ChangeMeterRequest::where('status', 3)->count() ?? 0,
        ];

        return [
            'today' => $today_count,
            'yesterday' => $yesterday_count,
            'old_transactions' => $old_transaction_count,
            'total' => $total_count
        ];
    }

    /**
     * API endpoint to get status counts (returns JSON response)
     */
    public function getChangeMeterRequestStatusCounts()
    {
        try {
            $data = $this->getStatusCountsArray();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching status counts: ' . $e->getMessage()
            ]);
        }
    }
}
