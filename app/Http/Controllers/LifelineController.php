<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lifeline;
use App\Models\ConsumersTable;
use App\Helpers\Helper;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;
use App;

class LifelineController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:lifeline-list|lifeline-create|lifeline-edit|lifeline-delete', ['only' => ['index']]);
         $this->middleware('permission:lifeline-create', ['only' => ['create', 'store', 'storeNonPoor']]);
         $this->middleware('permission:lifeline-edit', ['only' => ['edit','update', 'approveLifeline', 'uploadLifeline']]);
         $this->middleware('permission:lifeline-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lifeline_datas = Lifeline::with('district', 'municipality', 'barangay')->orderBy('id','DESC')->paginate(10);
        // dd($lifeline_datas);
        // $lifeline_data = "";
        return view('lifeline.index',compact('lifeline_datas'));
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

        $year = date("Y");
        $control_id = Helper::IDGenerator(new Lifeline, 'control_no', 4, $year);
        // dd($control_id);

        // dd($districts);
        return view('lifeline.create')->with(compact('districts', 'control_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate requests
        $this->validate($request, [
            // 'control_no' => ['required', 'string', 'max:255', 'unique:lifelines,control_no'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'municipality' => ['required', 'string', 'max:255'],
            'postal_code' => ['required'],
            'contact_no' => ['nullable', 'regex:/^((09))[0-9]{9}/', 'digits:11'],
            'date_of_birth' => ['required', 'string', 'max:255'],
            'marital_status' => ['required', 'string', 'max:255'],
            'ownership' => ['required', 'string', 'max:255'],
            'electric_service_details' => ['required'],
            'type_of_id' => ['required'],
            'id_no' => ['required', 'string', 'max:255', 'unique:lifelines,valid_id_no'],
            'date_of_application' => ['required'],
            'household_id_no' => ['required', 'string', 'max:255', 'unique:lifelines,pppp_id'],
        ]);

        if ($request->date_of_application < '2023-01-01' || $request->date_of_application == $request->date_of_birth) {
            return redirect()->back()->withError('Pls double Check the Date of Application!');
        }
        
        $year = date("Y");
        $control_id = Helper::IDGenerator(new Lifeline, 'control_no', 4, $year); /** Generate control no */

        // Insert Record
        Lifeline::create([
            "control_no" => $control_id,
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
            "maiden_name" => $request->maiden_name,
            "house_no_zone_purok_sitio" => $request->house_no_zone_purok_sitio,
            "street" => $request->street,
            "district_id" => $request->district,
            "municipality_id" => $request->municipality,
            "barangay_id" => $request->barangay,
            "postal_code" => $request->postal_code,
            "date_of_birth" => $request->date_of_birth,
            "marital_status" => $request->marital_status,
            "contact_no" => $request->contact_no,
            "account_no" => $request->electric_service_details,
            "ownership" => $request->ownership,
            "representative_id_no" => $request->representative_id_no,
            "representative_full_name" => $request->representative_fullname,
            "pppp_id" => $request->household_id_no,
            "valid_id_type" => $request->type_of_id,
            "valid_id_no" => $request->id_no,
            "date_of_application" => $request->date_of_application,
            "swdo_certificate_no" => $request->exists('swdo_certificate_no') ? $request->swdo_certificate_no : null,
            "annual_income" => $request->exists('annual_income') ? $request->annual_income : null,
            "validity_period" => $request->exists('validity_period') ? $request->validity_period : null,
            "application_status" => 0,
            "remarks" => $request->remarks,
        ]);

        return redirect(route('lifeline.index'))->withSuccess('Record Successfully Created!');
    }

    public function storeNonPoor(Request $request)
    {
        // validate requests
        $this->validate($request, [
            'control_no' => ['required', 'string', 'max:255', 'unique:lifelines,control_no'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'municipality' => ['required', 'string', 'max:255'],
            'postal_code' => ['required'],
            'contact_no' => ['nullable', 'regex:/^((09))[0-9]{9}/', 'digits:11'],
            'date_of_birth' => ['required', 'string', 'max:255'],
            'marital_status' => ['required', 'string', 'max:255'],
            'ownership' => ['required', 'string', 'max:255'],
            'electric_service_detail' => ['required','unique:lifelines,account_no'],
            'id_no' => ['required', 'string', 'max:255', 'unique:lifelines,valid_id_no'],
            'type_of_id' => ['required'],
            'date_of_application' => ['required'],
            'validity_period_from' => ['required'],
            'validity_period_to' => ['required'],
            'annual_income' => ['required'],
            'sdwo_certification' => ['required', 'string', 'max:255'],
        ]);

        $year = date("Y");
        $control_id = Helper::IDGenerator(new Lifeline, 'control_no', 4, $year); /** Generate control no */

        // Insert Record
        Lifeline::create([
            "control_no" => $control_id,
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
            "maiden_name" => $request->maiden_name,
            "house_no_zone_purok_sitio" => $request->house_no_zone_purok_sitio,
            "street" => $request->street,
            "district_id" => $request->district,
            "municipality_id" => $request->municipality,
            "barangay_id" => $request->barangay,
            "postal_code" => $request->postal_code,
            "date_of_birth" => $request->date_of_birth,
            "marital_status" => $request->marital_status,
            "contact_no" => $request->contact_no,
            "account_no" => $request->electric_service_detail,
            "ownership" => $request->ownership,
            "representative_id_no" => $request->representative_id_no,
            "representative_full_name" => $request->representative_fullname,
            "date_of_application" => $request->date_of_application,
            "pppp_id" => $request->exists('household_id_no') ? $request->household_id_no: null,
            "valid_id_no" => $request->id_no,
            "valid_id_type" => $request->type_of_id,
            "swdo_certificate_no" => $request->exists('sdwo_certification') ? $request->sdwo_certification : null ,
            "annual_income" => $request->exists('annual_income') ? $request->annual_income : null,
            "validity_period_from" => $request->exists('validity_period_from') ? $request->validity_period_from : null,
            "validity_period_to" => $request->exists('validity_period_to') ? $request->validity_period_to : null,
            "application_status" => 0,
            "remarks" => $request->remarks,
        ]);

        return redirect(route('lifeline.index'))->withSuccess('Record Successfully Created!');
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
        $lifeline = Lifeline::find($id);

        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();

        $account = DB::connection('sqlSrvBilling')
        ->table('Consumers Table')
        ->where('Accnt No', $lifeline->account_no)
        ->select('Accnt No as id', 'Name', 'Address')
        ->get();

        return view('lifeline.edit')->with(compact('districts','lifeline','account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request);

        if($request->exists('household_id_no')){
            // validate if 4ps applicant
            $this->validate($request, [
                'control_no' => ['required', 'string', 'max:255', 'unique:lifelines,control_no,'.$id],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'district' => ['required', 'string', 'max:255'],
                'municipality' => ['required', 'string', 'max:255'],
                'contact_no' => ['nullable', 'regex:/^((09))[0-9]{9}/', 'digits:11', 'unique:lifelines,contact_no,'.$id],
                'date_of_birth' => ['required', 'string', 'max:255'],
                'marital_status' => ['required', 'string', 'max:255'],
                'ownership' => ['required', 'string', 'max:255'],
                // 'electric_service_details' => ['required', 'unique:lifelines,account_no,'.$id],
                'id_no' => ['required', 'string', 'max:255', 'unique:lifelines,valid_id_no,'.$id],
                'type_of_id' => ['required'],
                'household_id_no' => ['required', 'string', 'max:255', 'unique:lifelines,pppp_id,'.$id],
            ]);
        }
        else{
            // validate if not 4ps applicant
            $this->validate($request, [
                'control_no' => ['required', 'string', 'max:255', 'unique:lifelines,control_no,'.$id],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'district' => ['required', 'string', 'max:255'],
                'municipality' => ['required', 'string', 'max:255'],
                'contact_no' => ['nullable', 'regex:/^((09))[0-9]{9}/', 'digits:11', 'unique:lifelines,contact_no,'.$id],
                'date_of_birth' => ['required', 'string', 'max:255'],
                'marital_status' => ['required', 'string', 'max:255'],
                'ownership' => ['required', 'string', 'max:255'],
                // 'electric_service_details' => ['required', 'unique:lifelines,account_no,'.$id],
                'id_no' => ['required', 'string', 'max:255', 'unique:lifelines,valid_id_no,'.$id],
                'type_of_id' => ['required'],
                'annual_income' => ['required'],
                'swdo_certification' => ['required', 'string', 'max:255', 'unique:lifelines,swdo_certificate_no,'.$id],
                'validity_period_from' => ['required'],
                'validity_period_to' => ['required'],
            ]);
        }
        

        // Update Record
        $lifeline = Lifeline::find($id);
        $lifeline->control_no = $request->control_no;
        $lifeline->first_name = $request->first_name;
        $lifeline->middle_name = $request->middle_name;
        $lifeline->last_name = $request->last_name;
        $lifeline->maiden_name = $request->maiden_name;
        $lifeline->house_no_zone_purok_sitio = $request->house_no_zone_purok_sitio;
        $lifeline->street = $request->street;
        $lifeline->district_id = $request->district;
        $lifeline->municipality_id = $request->municipality;
        $lifeline->barangay_id = $request->barangay;
        $lifeline->date_of_birth = $request->date_of_birth;
        $lifeline->marital_status = $request->marital_status;
        $lifeline->contact_no = $request->contact_no;
        // $lifeline->account_no = $request->electric_service_details;
        $lifeline->ownership = $request->ownership;
        $lifeline->representative_id_no = $request->representative_id_no;
        $lifeline->representative_full_name = $request->representative_fullname;
        $lifeline->pppp_id = $request->exists('household_id_no') ? $request->household_id_no: null;
        $lifeline->valid_id_no = $request->id_no;
        $lifeline->valid_id_type = $request->type_of_id;
        $lifeline->swdo_certificate_no = $request->exists('swdo_certification') ? $request->swdo_certification : null ;
        $lifeline->annual_income = $request->exists('annual_income') ? $request->annual_income : null;
        $lifeline->validity_period_from = $request->exists('validity_period_from') ? $request->validity_period_from : null;
        $lifeline->validity_period_to = $request->exists('validity_period_to') ? $request->validity_period_to : null;
        $lifeline->application_status = 0;
        $lifeline->date_of_application = $request->date_of_application;
        $lifeline->remarks = $request->remarks;
        $lifeline->save();

        return redirect(route('lifeline.index'))->withSuccess('Record Successfully Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $application = Lifeline::find($id);
        $application->delete();

        return redirect(route('lifeline.index'))->withSuccess('Record Successfully deleted!');
    }

    public function approveLifelineIndex()
    {
        $lifeline_data = Lifeline::orderBy('id','DESC')->where('application_status', 0)->paginate(10);
        // dd($lifeline_data);
        // $lifeline_data = "";
        return view('lifeline.approvals',compact('lifeline_data'));
    }

    public function approveLifeline(Request $request, string $id)
    {
        DB::beginTransaction();
        // dd($request->exists('disapproved'));
        if($request->exists('un_tag')){
            try {
                $update_account = DB::connection('sqlSrvBilling')
                    ->table('Consumers Table')
                    ->where('Accnt No', $request->account_no)
                    ->update([
                        'LFflag' => "NO",
                        'LFdate' => null,
                    ]);

                if($update_account == 0){
                    return redirect()->back()->withError("Invalid Account No.");
                }
                else{
                    Lifeline::find($id)->update([
                        'application_status' => 2,
                        'approved_by' =>  Auth::id(),
                        'approved_date' => Carbon::now(),
                    ]);

                    DB::commit();

                    return redirect()->back()->withSuccess('Application Successfully Untag!');
                    // all good
                }
                
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return redirect()->back()->withError($e->getMessage());
            }
        }else{
            // try {
            //     $update_account = DB::connection('sqlSrvBilling')
            //         ->table('Consumers Table')
            //         ->where('Accnt No', $request->account_no)
            //         ->update([
            //             'LFflag' => "Yes",
            //             'LFdate' => $request->date_of_application,
            //         ]);

            //     if($update_account == 0){
            //         return redirect()->back()->withError("Invalid Account No.");
            //     }
            //     else{
            //         Lifeline::find($id)->update([
            //             'application_status' => 1,
            //             'approved_by' =>  Auth::id(),
            //             'approved_date' => Carbon::now(),
            //         ]);

            //         DB::commit();

            //         return redirect()->route('lifelineCoverageCertificate',$id);
            //         // return redirect()->back()->withSuccess('Applications Successfully Approved!');
            //         // all good
            //     }
                
            // } catch (\Exception $e) {
            //     DB::rollback();
            //     // something went wrong
            //     return redirect()->back()->withError($e->getMessage());
            // }
        }

        
    }

    public function uploadLifeline()
    {
        try {
            // $lifelines = Lifeline::where('application_status', 0)->whereIn('control_no', ['2023-1286', '2023-1285'])->get()->take(5);
            $lifelines = Lifeline::where('application_status', 0)->get();
            DB::beginTransaction();
            foreach ($lifelines as $key => $lifeline) {

                DB::table('lifelines')
                ->where('id', $lifeline->id)
                ->update([
                    'application_status' => 1,
                    'approved_by' =>  Auth::id(),
                    'approved_date' => Carbon::now(),
                ]);

                DB::connection('sqlSrvBilling')
                    ->table('Consumers Table')
                    ->where('Accnt No', $lifeline->account_no)
                    ->update([
                        'LFflag' => "Yes",
                        'LFdate' => $lifeline->date_of_application,
                    ]);

            }
            DB::commit();

            return redirect()->back()->withSuccess('Applications Successfully Uploaded!');
            
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect()->back()->withError($e->getMessage());
        }
    }

    // Fetch records
    public function getAccountDetails(Request $request){
        $search = $request->search;
        $typeName = $request->has('byName') ? $request->byName : null;
        
        $lifeline_data = Lifeline::pluck('account_no');

        if ($typeName) {
            $accounts = DB::table('Consumers Table as ct')
            ->leftJoin('lifelines as ll', 'ct.Accnt No', '=', 'll.account_no')
            ->select('ct.Accnt No as id', 'ct.Name', 'ct.Address', 'ct.OR No', 'ct.Date', 'ct.Prev Reading', 'ct.Serial No')
            ->whereNull('ll.account_no') // Ensures only non-existing accounts are selected
            ->where('ct.Name', 'like', '%' .$search . '%');
        } else {
            if($search == ''){
                $accounts = DB::table('Consumers Table as ct')
                ->leftJoin('lifelines as ll', 'ct.Accnt No', '=', 'll.account_no')
                ->select('ct.Accnt No as id', 'ct.Name', 'ct.Address', 'ct.OR No', 'ct.Date', 'ct.Prev Reading', 'ct.Serial No')
                ->whereNull('ll.account_no'); // Ensures only non-existing accounts are selected

            } else{
                $accounts = DB::table('Consumers Table as ct')
                ->leftJoin('lifelines as ll', 'ct.Accnt No', '=', 'll.account_no')
                ->select('ct.Accnt No as id', 'ct.Name', 'ct.Address', 'ct.OR No', 'ct.Date', 'ct.Prev Reading', 'ct.Serial No')
                ->whereNull('ll.account_no') // Ensures only non-existing accounts are selected
                ->where('ct.Accnt No', 'like', '%' .$search . '%');
            }
        }
        $data = $accounts->paginate(10, ['*'], 'page', $request->page);
        // dd($data);
        return response()->json($data); 
    } 

    public function lifelineCoverageCertificate(Request $request, string $id)
    {
        $lifeline_data = Lifeline::find($id);
        // dd($lifeline_data->application_status);
        if($lifeline_data->application_status == 1){
            if($lifeline_data->pppp_id){
                view()->share('data', $lifeline_data);
                $pdf = PDF::loadView('lifeline.pppps_certification_of_lifeline_coverage_pdf');
                return $pdf->stream();
            }
            else{
                view()->share('data', $lifeline_data);
                $pdf = PDF::loadView('lifeline.non_pppps_certification_of_lifeline_coverage_pdf');
                return $pdf->stream();
            }
        }
        elseif($lifeline_data->application_status == 2){
            view()->share('data', $lifeline_data);
            $pdf = PDF::loadView('lifeline.untaging_certification_of_lifeline_coverage_pdf');
            return $pdf->stream();
        }
        
        
    }

    public function lifelineReport()
    {
        // $lifeline_datas = Lifeline::with('district', 'municipality', 'barangay')->orderBy('id','DESC')->paginate(10);
        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();
        // $lifeline_data = "";
        return view('lifeline.report.index',compact('districts'));
    }

    public function lifelineGenerateReport(Request $request)
    {
        if (!$request->status_type) {
            $non_four_ps = DB::table('lifelines')
            // ->select(DB::raw('first_name'))
            ->where('pppp_id', NULL)
            ->whereBetween('date_of_application', [$request->date_from, $request->date_to]);

            $four_ps = DB::table('lifelines')
            // ->select(DB::raw('first_name'))
            ->where('pppp_id','!=', NULL)
            ->whereBetween('date_of_application', [$request->date_from, $request->date_to]);
        }
        if ($request->status_type == 1) {
            $non_four_ps = DB::table('lifelines')
            // ->select(DB::raw('first_name'))
            ->where('pppp_id', NULL)
            ->where('application_status', 1)
            ->whereBetween('date_of_application', [$request->date_from, $request->date_to]);

            $four_ps = DB::table('lifelines')
            // ->select(DB::raw('first_name'))
            ->where('pppp_id','!=', NULL)
            ->where('application_status', 1)
            ->whereBetween('date_of_application', [$request->date_from, $request->date_to]);

        }
        if ($request->status_type == 2) {
            $non_four_ps = DB::table('lifelines')
            // ->select(DB::raw('first_name'))
            ->where('pppp_id', NULL)
            ->where('application_status', 2)
            ->whereBetween('date_of_application', [$request->date_from, $request->date_to]);

            $four_ps = DB::table('lifelines')
            // ->select(DB::raw('first_name'))
            ->where('pppp_id','!=', NULL)
            ->where('application_status', 2)
            ->whereBetween('date_of_application', [$request->date_from, $request->date_to]);
        }
        

        

        if($request->district){
            $four_ps = $four_ps->where('district_id', $request->district);
            $non_four_ps = $non_four_ps->where('district_id', $request->district);
        }
        if($request->municipality){
            $four_ps = $four_ps->where('municipality_id', $request->municipality);
            $non_four_ps = $non_four_ps->where('municipality_id', $request->municipality);
        }
        // dd();
        $four_ps_count = $four_ps->count();
        $non_four_ps_count = $non_four_ps->count();
        $requests = $request->all();

        $district_name = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('district_name')
        ->where('id', $requests['district'])
        ->pluck('district_name');

        $municipality_name = DB::connection('sqlSrvMembership')
        ->table('municipalities')
        ->select('municipality_name')
        ->where('id', $requests['municipality'])
        ->pluck('municipality_name');

        // dd($municipality_name);
        $pdf = PDF::loadView('lifeline.report.generate_report_qualified', compact('requests', 'four_ps_count', 'non_four_ps_count', 'district_name', 'municipality_name'));
        return $pdf->stream();    
        
    }

    public function fetchLifelineApplication(Request $request)
    {
        $lifeline_datas = Lifeline::orderBy('id', 'desc')->paginate(10);
        
        if($request->ajax()){
            
            $lifeline_datas = Lifeline::where('control_no', 'LIKE', '%'.$request->control_number.'%')
            ->where('first_name', 'LIKE', '%'.$request->fname.'%')
            ->where('last_name', 'LIKE', '%'.$request->lname.'%')
            ->paginate(10);
            
            return view('lifeline.search')->with(compact('lifeline_datas'))->render();
        }

        return view('lifeline.index')->with(compact('lifeline_datas'));
    }

 
}
