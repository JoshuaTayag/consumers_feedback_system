<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChangeMeterRequest;
use App\Models\ChangeMeterRequestFees;
use App\Models\ChangeMeterRequestPostingHistory;
use App\Services\ChangeMeterService;
use DB;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;
use PDO;

class ChangeMeterRequestController extends Controller
{
    protected $changeMeterService;

    function __construct(ChangeMeterService $changeMeterService)
    {
         $this->changeMeterService = $changeMeterService;
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
        $cm_requests = ChangeMeterRequest::with('municipality', 'barangay')->orderBy('id','desc')->paginate(9);
        $ref_employees = DB::table('change_meter_contractors')
        ->select(DB::raw("CONCAT(last_name, ', ', first_name) AS full_name"), 'id')
        ->orderBy('last_name', 'ASC')
        ->get();
        return view('service_connect_order.change_meter.index',compact('cm_requests', 'ref_employees'));
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

        $type_of_meters = DB::connection('sqlSrvHousewiring')
        ->table('khw_meter_types')
        ->select('*')
        ->orderBy('meter_code', 'asc')
        ->get();
        
        // dd($districts);
        return view('service_connect_order.change_meter.create')->with(compact( 'municipalities', 'consumer_types', 'occupancy_types', 'type_of_meters'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $change_meter_request_exists = ChangeMeterRequest::where('account_number', $request->electric_service_detail)
        ->where('status', null);
        // dd($change_meter_request_exists->first('control_no')->control_no);

        if ($change_meter_request_exists->exists()) {
            // Record exists
            return redirect(route('indexCM'))->withWarning('This account has pending request! </br> Control No:'.$change_meter_request_exists->first('control_no')->control_no);
        } else { 

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
                // 'membership_date' => ['required'],
                // 'consumer_type' => ['required'],
                'meter_code_no' => ['required'],
                'process_date' => ['required'],
                'meter_no' => ['nullable', 'unique:sqlSrvHousewiring.Service Connect Table,MeterNo'],
            ]);

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
                    "new_meter_no" => null,
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
                ]);

                
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
        $change_meter_request = ChangeMeterRequest::with('cmr_fees')->find($id);
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

            $type_of_meters = DB::connection('sqlSrvHousewiring')
            ->table('khw_meter_types')
            ->select('*')
            ->orderBy('meter_code', 'asc')
            ->get();
            
            return view('service_connect_order.change_meter.edit')->with(compact('change_meter_request', 'barangays', 'municipalities', 'consumer_types', 'occupancy_types', 'type_of_meters'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate requests
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'area' => ['required'],
            'barangay' => ['required'],
            'municipality' => ['required'],
            'contact_no' => ['nullable', 'regex:/^((09))[0-9]{9}/', 'digits:11'],
            'membership_or' => ['required'],
            // 'membership_date' => ['required'],
            'consumer_type' => ['required'],
            'meter_code_no' => ['required'],
            'meter_no' => ['nullable', 'unique:sqlSrvHousewiring.Service Connect Table,MeterNo,' . $id . ',id'], // Ensure uniqueness except for the current record
        ]);

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
                "new_meter_no" => null,
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
            $change_meter_request->delete();

            $change_meter_request_fees = ChangeMeterRequestFees::where('cm_control_no', $id);
            $change_meter_request_fees->delete();

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

                DB::connection('sqlSrvBilling')
                ->table('Consumers Table')
                ->where('Accnt No', $change_meter_request->account_number)
                ->update([
                    'Serial No' => $change_meter_request->new_meter_no,
                    'Remarks' => $newRemarks,
                ]);

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
        $cm_requests = $cm_request->orderBy('control_no','DESC')->paginate(9);

        $ref_employees = DB::table('change_meter_contractors')
        ->select(DB::raw("CONCAT(last_name, ', ', first_name) AS full_name"), 'id')
        ->orderBy('last_name', 'ASC')
        ->get();

        // return view('products.index', compact('products'));
        return view('service_connect_order.change_meter.index',compact('cm_requests','ref_employees'));
    }

    public function view(string $id)
    {
        $cm_request = ChangeMeterRequest::find($id);
        return view('service_connect_order.change_meter.view_acted_request',compact('cm_request'));
    }

    public function viewReport(Request $request)
    {
        $municipalities = DB::connection('sqlSrvMembership')
        ->table('municipalities')
        ->select('*')
        ->orderBy('municipality_name', 'asc')
        ->get();

        return view('service_connect_order.change_meter.report', compact('municipalities'));
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

        // Execute the query and get the results
        $change_meter_requests = $query->get();

        view()->share('datas', $change_meter_requests);
        $pdf = PDF::loadView('service_connect_order.change_meter.pdf_reports')->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function getAccountDetails(Request $request){
        $search = $request->search;

            if($search == ''){
                $accounts = DB::table('Consumers Table as ct')
                ->select('ct.Accnt No as id', 'ct.Name', 'ct.Address', 'ct.OR No', 'ct.Date', 'ct.Prev Reading', 'ct.Serial No', 'ct.Cons Type');

            } else{
                $accounts = DB::table('Consumers Table as ct')
                ->select('ct.Accnt No as id', 'ct.Name', 'ct.Address', 'ct.OR No', 'ct.Date', 'ct.Prev Reading', 'ct.Serial No', 'ct.Cons Type')
                ->where('ct.Accnt No', 'like', '%' .$search . '%');
            }

        $data = $accounts->paginate(10, ['*'], 'page', $request->page);
        // dd($data);
        return response()->json($data); 
    } 

}
