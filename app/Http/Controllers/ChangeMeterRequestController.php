<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChangeMeterRequest;
use DB;

class ChangeMeterRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cm_requests = ChangeMeterRequest::orderBy('id','asc')->paginate(9);
        $ref_employees = DB::table('ref_employees')
        ->select(DB::raw("CONCAT(last_name, ', ', SUBSTRING(first_name, 1, 1), '. ', SUBSTRING(middle_name, 1, 1)) AS full_name"))
        ->where('department', 'TSD')
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
            'membership_date' => ['required'],
            'consumer_type' => ['required'],
            'meter_code_no' => ['required'],
            'meter_no' => ['nullable', 'unique:sqlSrvHousewiring.Service Connect Table,MeterNo'],
        ]);

        // $year = date("Y");
        $control_id = Helper::IDGeneratorChangeMeter(new ChangeMeterRequest, 'control_number', 5, 'CM');
        // dd($request);
        DB::beginTransaction();
        try {
            // Perform the first operation (creating a record in ServiceConnectOrder)
            $sco = ServiceConnectOrder::create([
                "SCONo" => $control_id,
                "Lastname" => $request->last_name,
                "Firstname" => $request->first_name,
                "ProcessDate" => Carbon::today(),
                "Membership OR#" => $request->membership_or,
                "Membership Date" => $request->membership_date,
                // "MeterNo" => $request->meter_no,
                "Date Installed" => $request->date_installed,
                "Area" => $request->area,
                "Brgy" => $request->barangay,
                "Municipality" => $request->municipality,
                "Sitio" => $request->sitio,
                "ConsumerType" => $request->consumer_type,
                "CodeNo" => $request->meter_code_no,
                "Remarks" => $request->remarks,
                "Location" => $request->location,
                "LogName" => Auth::user()->name,
                "NextAcctNo" => $request->electric_service_detail,
                "ContactNo" => $request->contact_no,
                "LastRdg" => $request->last_reading,
                "OldMtr" => $request->old_meter,
                // "TurnOffOn" => $request->occupancy_type,
                // "LineType" => $request->line_type,
                "Meter OR#" => $request->meter_or_no,
                "Rdg initial" => $request->reading_initial,
                "Location" => $request->location,
                "Feeder" => $request->feeder,
                "Spouse" => $request->care_of,
                "keyoff" => $request->care_of ? true : false,
                "application_type" => 'CHANGE METER'
            ]);

            // dd($sco->SCONo);
            DB::connection('sqlSrvHousewiring')->table('Transaction Table')->insert(
                array("SCONo" => $sco->SCONo,
                        "Membership Fee" => $request->membership ? $request->membership : 0.00,
                        "Energy Conmp Deposit" => $request->energy_deposit ? $request->energy_deposit : 0.00,
                        "Connection Fee" => $request->conn_fee ? $request->conn_fee : 0.00,
                        "Xformer Rental" => $request->xformer_rental ? $request->xformer_rental : 0.00,
                        "Xformer Test" => $request->xformer_test ? $request->xformer_test : 0.00,
                        "Xformer Installation" => $request->xformer_installation ? $request->xformer_installation : 0.00,
                        "Xformer Removal" => $request->xformer_removal ? $request->xformer_removal : 0.00,
                        "Cons Xformer" => $request->consumer_xfmr ? $request->consumer_xfmr : 0.00,
                        "Cons XPole" => $request->consumer_pole ? $request->consumer_pole : 0.00,
                        "Grounding Clamp" => $request->grounding_clamp ? $request->grounding_clamp : 0.00,
                        "Grounding Rod" => $request->grounding_rod ? $request->grounding_rod : 0.00,
                        "MeterSeal" => $request->meter_seal ? $request->meter_seal : 0.00,
                        "Hotline Clamp" => $request->hotline_clamp ? $request->hotline_clamp : 0.00,
                        "Kwhm Deposit" => $request->meter_accessories ? $request->meter_accessories : 0.00,
                        "RejectionFee" => $request->discredit_fee ? $request->discredit_fee : 0.00,
                        "Calibration" => $request->calibration_fee ? $request->calibration_fee : 0.00,
                        "Others IDLamination" => $request->others ? $request->others : 0.00,
                        "HousewringKit" => $request->housewiring_kit ? $request->housewiring_kit : 0.00,
                        "ExcessWire" => $request->excess_conductor ? $request->excess_conductor : 0.00,
                        // "WireExcess" => $request->conductor_duplex ? $request->conductor_duplex : 0.00,
                        "circuit_breaker" => $request->circuit_breaker ? $request->circuit_breaker : 0.00,
                        )
            );

            DB::commit();

            return redirect(route('indexCM'))->withSuccess('Record Successfully Created! </br> SCO No:'.$sco->SCONo);

        } catch (\Exception $e) {
            // If an exception occurs during the transaction, rollback all changes
            DB::rollback();
            
            // Optionally, handle the exception (log it, display an error message, etc.)
            // For example:
            // Log::error($e->getMessage());
            return response()->json(['error' => $e], 500);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
