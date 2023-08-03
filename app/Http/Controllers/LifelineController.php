<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lifeline;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LifelineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lifeline_data = Lifeline::orderBy('id','DESC')->paginate(10);
        // $lifeline_data = "";
        return view('lifeline.index',compact('lifeline_data'));
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
        return view('lifeline.create')->with(compact('districts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate if application type  is 4ps or not
        if($request->applicant_type == 1){
            // validate requests
            // dd($request);
            $this->validate($request, [
                'control_no' => ['required', 'string', 'max:255', 'unique:lifelines,control_no'],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'district' => ['required', 'string', 'max:255'],
                'municipality' => ['required', 'string', 'max:255'],
                'contact_no' => ['nullable', 'regex:/^((09))[0-9]{9}/', 'digits:11'],
                'date_of_birth' => ['required', 'string', 'max:255'],
                'marital_status' => ['required', 'string', 'max:255'],
                'ownership' => ['required', 'string', 'max:255'],
                'electric_service_details' => ['required'],
                'id_no' => ['required', 'string', 'max:255', 'unique:lifelines,valid_id_no'],
                'household_id_no' => ['required', 'string', 'max:255', 'unique:lifelines,pppp_id'],
            ]);

            // Insert Record
            Lifeline::create([
                "control_no" => $request->control_no,
                "first_name" => $request->first_name,
                "middle_name" => $request->middle_name,
                "last_name" => $request->last_name,
                "maiden_name" => $request->maiden_name,
                "house_no_zone_purok_sitio" => $request->house_no_zone_purok_sitio,
                "street" => $request->street,
                "district_id" => $request->district,
                "municipality_id" => $request->municipality,
                "barangay_id" => $request->barangay,
                "date_of_birth" => $request->date_of_birth,
                "marital_status" => $request->marital_status,
                "contact_no" => $request->contact_no,
                "account_no" => $request->electric_service_details,
                "ownership" => $request->ownership,
                "representative_id_no" => $request->representative_id_no,
                "representative_full_name" => $request->representative_fullname,
                "pppp_id" => $request->household_id_no,
                "valid_id_no" => $request->id_no,
                "swdo_certificate_no" => $request->exists('swdo_certificate_no') ? $request->swdo_certificate_no : null,
                "annual_income" => $request->exists('annual_income') ? $request->annual_income : null,
                "validity_period" => $request->exists('validity_period') ? $request->validity_period : null,
                "application_status" => 0,
                "remarks" => $request->remarks,
            ]);

            return redirect(route('lifeline.index'))->withSuccess('Record Successfully Created!');
        }
        else{
            // validate requests
            $this->validate($request, [
                'control_no' => ['required', 'string', 'max:255', 'unique:lifelines,4ps_id'],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'district' => ['required', 'string', 'max:255'],
                'municipality' => ['required', 'string', 'max:255'],
                'contact_no' => ['regex:/^((09))[0-9]{9}/', 'digits:11'],
                'date_of_birth' => ['required', 'string', 'max:255'],
                'marital_status' => ['required', 'string', 'max:255'],
                'ownership' => ['required', 'string', 'max:255'],
                'electric_service_details' => ['required'],
            ]);
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
        if($request->exists('disapproved')){
            DB::table('lifelines')
                ->where('id', $id)
                ->update([
                    'application_status' => 2,
                    'approved_by' =>  Auth::id(),
                    'approved_date' => Carbon::now(),
                ]);

            DB::commit();
            return redirect()->back()->withSuccess('Application Disapproved!');
        }else{
            try {
                $update_account = DB::connection('sqlSrvBilling')
                    ->table('Consumers Table')
                    ->where('Accnt No', $request->account_no)
                    ->update([
                        'LFflag' => "Yes",
                        'LFdate' => Carbon::now(),
                    ]);

                if($update_account == 0){
                    return redirect()->back()->withError("Invalid Account No.");
                }
                else{
                    DB::table('lifelines')
                    ->where('id', $id)
                    ->update([
                        'application_status' => 1,
                        'approved_by' =>  Auth::id(),
                        'approved_date' => Carbon::now(),
                    ]);

                    DB::commit();

                    return redirect()->back()->withSuccess('Applications Successfully Approved!');
                    // all good
                }
                
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return redirect()->back()->withError($e->getMessage());
            }
        }

        
    }

    public function approveLifelineMultiple(Request $request)
    {
        // dd($request->exists('disapproved'));
        DB::beginTransaction();

        if($request->exists('disapproved')){
            Lifeline::whereIn('id', $request->ids)->update([
                'application_status' => 2,
                'approved_by' =>  Auth::id(),
                'approved_date' => Carbon::now(),
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Applications Disapproved!",
            ]);
        }else{
            try {

                $update_account = DB::connection('sqlSrvBilling')
                    ->table('Consumers Table')
                    ->whereIn('Accnt No', $request->accounts)
                    ->update([
                        'LFflag' => "Yes",
                        'LFdate' => Carbon::now(),
                    ]);

                if($update_account == 0){
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid Account No.",
                    ]);
                }
                else{
                    Lifeline::whereIn('id', $request->ids)->update([
                        'application_status' => 1,
                        'approved_by' =>  Auth::id(),
                        'approved_date' => Carbon::now(),
                    ]);
        
                    DB::commit();
                    return response()->json([
                        'success' => true,
                        'message' => "Applications Successfully Approved",
                    ]);
                    // all good
                }

                // all good
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
            }
        }

        
    } 

    // Fetch records
    public function getAccountDetails(Request $request){
        $search = $request->search;

        if($search == ''){
        $accounts = DB::connection('sqlSrvBilling')
        ->table('Consumers Table')
        ->select('Accnt No as id', 'Name', 'Address');
        }else{
        $accounts = DB::connection('sqlSrvBilling')
        ->table('Consumers Table')
        ->where('Accnt No', 'like', '%' .$search . '%')
        ->select('Accnt No as id', 'Name', 'Address');
        }
        $data = $accounts->paginate(10, ['*'], 'page', $request->page);
        return response()->json($data); 
    } 
}
