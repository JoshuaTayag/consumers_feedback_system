<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceConnectOrder;
use DB;
use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;

class ServiceConnectOrderController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:service-connect-order-list|service-connect-order-create|service-connect-order-edit|service-connect-order-delete', ['only' => ['index','store']]);
         $this->middleware('permission:service-connect-order-create', ['only' => ['create','store']]);
         $this->middleware('permission:service-connect-order-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:service-connect-order-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scos = ServiceConnectOrder::orderBy('application_id','asc')->where('SCONo', 'like', 'h%')->orderBy('SCONo', 'asc')->paginate(9);
        return view('service_connect_order.index',compact('scos'));
    }

    public function indexCM()
    {
        $scos = ServiceConnectOrder::orderBy('SCONo','DESC')->where('application_type', 'CHANGE METER')->paginate(9);
        $ref_employees = DB::table('ref_employees')
        ->select(DB::raw("CONCAT(last_name, ', ', SUBSTRING(first_name, 1, 1), '. ', SUBSTRING(middle_name, 1, 1)) AS full_name"))
        ->where('department', 'TSD')
        ->orderBy('last_name', 'ASC')
        ->get();
        // dd($ref_employees);
        return view('service_connect_order.change_meter.index',compact('scos','ref_employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();

        // dd($districts);
        return view('service_connect_order.create')->with(compact('districts'));
    }

    public function createCM()
    {
        $sitios = DB::connection('sqlSrvMembership')
        ->table('Sitio Table')
        ->select('*')
        ->orderBy('Sitio', 'asc')
        ->get();

        $barangays = DB::connection('sqlSrvMembership')
        ->table('Barangay Table')
        ->select('*')
        ->orderBy('Brgy', 'asc')
        ->get();

        $municipalities = DB::connection('sqlSrvMembership')
        ->table('Municipality Table')
        ->select('*')
        ->orderBy('Municipality', 'asc')
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
        return view('service_connect_order.change_meter.create')->with(compact('barangays', 'sitios', 'municipalities', 'consumer_types', 'occupancy_types', 'type_of_meters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function storeCM(Request $request)
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
        $control_id = Helper::IDGeneratorChangeMeter(new ServiceConnectOrder, 'SCONo', 4, 'CM');
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

    public function editCM(string $id)
    {
        $sco_cm = ServiceConnectOrder::find($id);

        $sitios = DB::connection('sqlSrvMembership')
        ->table('Sitio Table')
        ->select('*')
        ->orderBy('Sitio', 'asc')
        ->get();

        $barangays = DB::connection('sqlSrvMembership')
        ->table('Barangay Table')
        ->select('*')
        ->orderBy('Brgy', 'asc')
        ->get();

        $municipalities = DB::connection('sqlSrvMembership')
        ->table('Municipality Table')
        ->select('*')
        ->orderBy('Municipality', 'asc')
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

        $fees = DB::connection('sqlSrvHousewiring')->table('Transaction Table')->where('SCONo', $sco_cm->SCONo)->get();
        
        // dd($fees[0]->{'Membership Fee'});
        return view('service_connect_order.change_meter.edit')->with(compact('fees', 'sco_cm', 'barangays', 'sitios', 'municipalities', 'consumer_types', 'occupancy_types', 'type_of_meters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function meterPostingCM(Request $request)
    {
        DB::beginTransaction();
        try {
            $sco = DB::connection('sqlSrvHousewiring')->table('Service Connect Table')
                ->where('SCONo', $request->sco);
            // Perform the first operation (updating a record in ServiceConnectOrder)           
            if ($sco) {
                $sco->update([
                    "MeterNo" => $request->meter_no,
                    "Date Installed" => $request->date_installed ? date('Y-m-d H:i:s', strtotime($request->date_installed)) : null,
                    "SealNo" => $request->seal_no,
                    "SerialNo" => $request->meter_no,
                    "ERC Seal#" => $request->erc_seal,
                    "Spouse" => $request->care_of,
                    "Feeder" => $request->feeder,
                    "Area" => $request->area,
                    "LastRdg" => $request->last_reading,
                    "Rdg Initial" => $request->reading_initial,
                    "Crew" => $request->crew,
                    "Dispatch2" => $request->status,
                    "KvaType" => $request->time,
                    "Ownership" => $request->damage_cause,
                    "CrewRemarks" => $request->crew_remarks,
                    "Acted" => 1,
                ]);
                // dd($sco->first()->OldMtr);
                DB::table('posted_meters_history')
                ->insert([
                    "sco_no" => $request->sco,
                    "old_meter_no" => $sco->first()->OldMtr,
                    "new_meter_no" => $request->meter_no,
                    "process_date" => $sco->first()->ProcessDate,
                    "date_installed" => $request->date_installed ? date('Y-m-d H:i:s', strtotime($request->date_installed)) : null,
                    "action_status" => $request->status,
                    "area" => $request->area,
                    "feeder" => $request->feeder,
                    "leyeco_seal_no" => $request->seal_no,
                    "serial_no" => $request->meter_no,
                    "erc_seal_no" => $request->erc_seal,
                    "posted_by" => Auth::id(),
                    "created_at" => Carbon::now(),
                ]);

                $billing = DB::connection('sqlSrvBilling')
                    ->table('Consumers Table')
                    ->where('Accnt No', $sco->first()->NextAcctNo)
                    ->update([
                        'Serial No' => $request->meter_no,
                    ]);
            } else {
                return redirect(route('indexCM'))->withError('No Record Found!');
            }
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

    public function updateCM(Request $request, string $id)
    {
        // validate requests
        $this->validate($request, [
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

        DB::beginTransaction();
        try {
            $sco = ServiceConnectOrder::find($id);
            // Perform the first operation (updating a record in ServiceConnectOrder)           
            if ($sco) {
                $sco->update([
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
                    // "NextAcctNo" => $request->electric_service_detail,
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
                ]);
            } else {
                return redirect(route('indexCM'))->withError('No Record Found!');
            }
            // dd($sco->SCONo);
            DB::connection('sqlSrvHousewiring')->table('Transaction Table')
            ->where('SCONo', $sco->SCONo)
            ->update([
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
            ]);

            DB::commit();

            return redirect(route('indexCM'))->withSuccess('Record Successfully Updated! </br> SCO No:'.$sco->SCONo);

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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function printChangeMeterRequest(Request $request, string $id)
    {
        $sco = ServiceConnectOrder::find($id);
        // dd($sco->SCONo);
        view()->share('data', $sco);
        $pdf = PDF::loadView('service_connect_order.change_meter.print_cm_request_pdf');
        return $pdf->stream();
    }

    public function fetchServiceConnectApplications(Request $request)
    {
        $scos = ServiceConnectOrder::orderBy('SCONo','DESC')->where('SCONo', 'like', 'CM%')->paginate(9);

        $ref_employees = DB::table('ref_employees')
        ->select(DB::raw("CONCAT(last_name, ', ', SUBSTRING(first_name, 1, 1), '. ', SUBSTRING(middle_name, 1, 1)) AS full_name"))
        ->where('department', 'TSD')
        ->orderBy('last_name', 'ASC')
        ->get();

        // $lifeline_datas = Lifeline::orderBy('id', 'desc')->paginate(10);
        
        if($request->ajax() && ($request->sco_no || $request->f_name || $request->meter_no) ){
            
            $scos = ServiceConnectOrder::where('SCONo', 'LIKE', ''.$request->sco_no.'%')
            ->where('Firstname', 'LIKE', '%'.$request->f_name.'%')
            ->where('MeterNo', 'LIKE', '%'.$request->meter_no.'%')
            ->orderBy('SCONo','DESC')
            ->paginate(9);
            // dd('sample');
            return view('service_connect_order.change_meter.search')->with(compact('scos','ref_employees'))->render();
        }

        // return view('lifeline.index')->with(compact('scos'));
        return view('service_connect_order.change_meter.search')->with(compact('scos','ref_employees'))->render();
        // return view('service_connect_order.change_meter.search',compact('scos','ref_employees'));
    }

    function validateMeterPosting(Request $request)
    {
        if($request->get('meter_no')) {
            $meter_no = $request->get('meter_no');
            $data = DB::connection('sqlSrvHousewiring')->table('Service Connect Table')
                    ->where('MeterNo', $meter_no);
            if($data->count() > 0)
            {
            $sco_no = $data->first()->SCONo;
            return ['not_unique', $sco_no];
            }
            else
            {
            return ['unique', null];
            }
        }

        if($request->get('seal_no')) {
            $seal_no = $request->get('seal_no');
            $data = DB::connection('sqlSrvHousewiring')->table('Service Connect Table')
                    ->where('SealNo', $seal_no);
            if($data->count() > 0)
            {
            $sco_no = $data->first()->SCONo;
            return ['not_unique', $sco_no];
            }
            else
            {
            return ['unique', null];
            }
        }

        if($request->get('erc_seal'))
        {
            $erc_seal = $request->get('erc_seal');
            $data = DB::connection('sqlSrvHousewiring')->table('Service Connect Table')
                    ->where('ERC Seal#', $erc_seal);
            if($data->count() > 0)
            {
            $sco_no = $data->first()->SCONo;
            return ['not_unique', $sco_no];
            }
            else
            {
            return ['unique', null];
            }
        }
    }

    public function searchCM(Request $request)
    {
        $sco_no = $request->input('sco_no');
        $f_name = $request->input('first_name');
        $l_name = $request->input('last_name');
        $meter_no = $request->input('meter_no');
        // $products = Product::where('name', 'like', "%$query%")->get();
        $scos = ServiceConnectOrder::query()
        ->where('application_type', 'CHANGE METER');

        if ($sco_no !== null && $sco_no !== '') {
            $scos->where('SCONo', 'like', "%$sco_no%");
        }

        if ($f_name !== null && $f_name !== '') {
            $scos->where('Firstname', 'like', "%$f_name%");
        }

        if ($l_name !== null && $l_name !== '') {
            $scos->where('Lastname', 'like', "%$l_name%");
        }

        if ($meter_no !== null && $meter_no !== '') {
            $scos->where('MeterNo', 'like', "%$meter_no%");
        }
        $scos = $scos->orderBy('SCONo','DESC')->paginate(9);

        // dd($scos);
        $ref_employees = DB::table('ref_employees')
        ->select(DB::raw("CONCAT(last_name, ', ', SUBSTRING(first_name, 1, 1), '. ', SUBSTRING(middle_name, 1, 1)) AS full_name"))
        ->where('department', 'TSD')
        ->orderBy('last_name', 'ASC')
        ->get();
        // return view('products.index', compact('products'));
        return view('service_connect_order.change_meter.index',compact('scos','ref_employees'));
    }
}
