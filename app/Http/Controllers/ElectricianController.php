<?php

namespace App\Http\Controllers;

use App\Models\Electrician;
use App\Models\BarangayElectrician\ElectricianComplaint;
use App\Models\BarangayElectrician\ElectricianEducationalBackground;
use Illuminate\Http\Request;
use DB;
use Image;
use App\Helpers\Helper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class ElectricianController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:electrician-list|electrician-create|electrician-edit|electrician-delete', ['only' => ['index', 'electricianComplaintIndex', 'electricianComplaintView']]);
         $this->middleware('permission:electrician-create', ['only' => ['create', 'store', 'electricianComplaintCreate', 'electricianComplaintStore']]);
         $this->middleware('permission:electrician-edit', ['only' => ['edit','update', 'electricianComplaintEdit', 'electricianComplaintUpdate']]);
         $this->middleware('permission:electrician-delete', ['only' => ['destroy', 'electricianComplaintDelete']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Electrician::orderBy('id','asc')->paginate(10);
        return view('electrician.index')->with(compact('data'));
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
        $control_id = Helper::IDGenerator(new Electrician, 'control_number', 4, $year);
        // dd($control_id);

        // dd($districts);
        return view('electrician.create')->with(compact('districts', 'control_id'));
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
            'civil_status' => ['required'],
            'date_of_birth' => ['required', 'string', 'max:255'],
            'type_of_id' => ['required'],
            'valid_id_no' => ['required', 'string', 'max:255'],
            'contact_no' => ['nullable', 'regex:/^((09))[0-9]{9}/', 'digits:11'],
            'application_type' => ['required'],
            'application_status' => ['required'],
            'district' => ['required'],
            'municipality' => ['required'],
            // 'membership_or' => ['required'],
            // 'membership_date' => ['required'],
            // 'electric_service_details' => ['required'],
        ]);

        $year = date("Y");
        $control_id = Helper::IDGenerator(new Electrician, 'control_number', 4, $year); /** Generate control no */
        $now = DB::raw('CURRENT_TIMESTAMP');
        // dd($request);
        DB::transaction(function () use ($request, $control_id, $now) {

            // INSERT ELECTRICIAN DETAILS
            // $electrician_id = DB::table('barangay_electricians')->insertGetId(
            //     array(
            //         'control_number' => $control_id,
            //         'first_name' => $request->first_name,
            //         'middle_name' => $request->middle_name,
            //         'last_name' => $request->last_name,
            //         'name_ext' => $request->name_ext,
            //         'sex' => $request->sex,
            //         'civil_status' => $request->civil_status,
            //         'date_of_birth' => $request->date_of_birth,
            //         'email_address' => $request->email_address,
            //         'fb_account' => $request->facebook_account,
            //         'spouse_first_name' => $request->spouse_first_name,
            //         'spouse_middle_name' => $request->spouse_middle_name,
            //         'spouse_last_name' => $request->spouse_last_name,
            //         'valid_id_type' => $request->type_of_id,
            //         'valid_id_number' => $request->valid_id_no,
            //         'application_type' => $request->application_type,
            //         'application_status' => $request->application_status,
                    
            //         'tesda_course_title' => $request->exists('course_title') ? $request->course_title : null ,
            //         'tesda_name_of_school' => $request->exists('name_of_school') ? $request->name_of_school : null ,
            //         'tesda_national_certificate_no' => $request->exists('certificate_no') ? $request->certificate_no : null ,
            //         'tesda_date_issued' => $request->exists('tesda_date_issued') ? $request->tesda_date_issued : null ,
            //         'tesda_valid_until_date' => $request->exists('tesda_validity') ? $request->tesda_validity : null ,

            //         'rme_license_no' => $request->exists('rme_license_no') ? $request->rme_license_no : null ,
            //         'rme_issued_on' => $request->exists('rme_license_issued_on') ? $request->rme_license_issued_on : null ,
            //         'rme_issued_at' => $request->exists('rme_license_issued_at') ? $request->rme_license_issued_at : null ,
            //         'rme_valid_until' => $request->exists('rme_validity') ? $request->rme_validity : null ,

            //         'ree_license_no' => $request->exists('ree_license_no') ? $request->ree_license_no : null ,
            //         'ree_issued_on' => $request->exists('ree_license_issued_on') ? $request->ree_license_issued_on : null ,
            //         'ree_issued_at' => $request->exists('ree_license_issued_at') ? $request->ree_license_issued_at : null ,
            //         'ree_valid_until' => $request->exists('ree_validity') ? $request->ree_validity : null ,
            //         'remarks' => $request->remarks,
            //         'application_status_remarks' => $request->application_remarks,
            //         'date_of_application' => $request->date_of_application,
            //         'created_by' => Auth::id(),

            //         'created_at' => $now
            //     )
            // );

            $electrician = Electrician::create([
                'control_number' => $control_id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'name_ext' => $request->name_ext,
                'sex' => $request->sex,
                'civil_status' => $request->civil_status,
                'date_of_birth' => $request->date_of_birth,
                'email_address' => $request->email_address,
                'fb_account' => $request->facebook_account,
                'spouse_first_name' => $request->spouse_first_name,
                'spouse_middle_name' => $request->spouse_middle_name,
                'spouse_last_name' => $request->spouse_last_name,
                'valid_id_type' => $request->type_of_id,
                'valid_id_number' => $request->valid_id_no,
                'application_type' => $request->application_type,
                'application_status' => $request->application_status,
            
                'tesda_course_title' => $request->has('course_title') ? $request->course_title : null,
                'tesda_name_of_school' => $request->has('name_of_school') ? $request->name_of_school : null,
                'tesda_national_certificate_no' => $request->has('certificate_no') ? $request->certificate_no : null,
                'tesda_date_issued' => $request->has('tesda_date_issued') ? $request->tesda_date_issued : null,
                'tesda_valid_until_date' => $request->has('tesda_validity') ? $request->tesda_validity : null,
            
                'rme_license_no' => $request->has('rme_license_no') ? $request->rme_license_no : null,
                'rme_issued_on' => $request->has('rme_license_issued_on') ? $request->rme_license_issued_on : null,
                'rme_issued_at' => $request->has('rme_license_issued_at') ? $request->rme_license_issued_at : null,
                'rme_valid_until' => $request->has('rme_validity') ? $request->rme_validity : null,
            
                'ree_license_no' => $request->has('ree_license_no') ? $request->ree_license_no : null,
                'ree_issued_on' => $request->has('ree_license_issued_on') ? $request->ree_license_issued_on : null,
                'ree_issued_at' => $request->has('ree_license_issued_at') ? $request->ree_license_issued_at : null,
                'ree_valid_until' => $request->has('ree_validity') ? $request->ree_validity : null,
            
                'primary_contact_no' => $request->contact_no,
                'secondary_contact_no' => $request->contact_no_ext,

                'remarks' => $request->remarks,
                'application_status_remarks' => $request->application_remarks,
                'date_of_application' => $request->date_of_application,
                'created_by' => Auth::id(),
            ]);
            
            $electrician_id = $electrician->id; // Get the inserted ID

            //  INSERT EDUCATIONAL BACKGROUND TABLE     
            $educ_background = $request->educationalBackground;
            if($educ_background){
                foreach ($educ_background as $key => $value) {
                    ElectricianEducationalBackground::create([
                        'electrician_id' => $electrician_id,
                        'educational_stage' => $value['educational_stage'],
                        'name_of_school' => $value['name_of_school'],
                        'degree_recieved' => $value['degree_recieved'],
                        'year_graduated' => $value['year_graduated'],
                        'created_at' => $now,
                    ]);
                }
            }
            
            //  INSERT CONTACT NUMBERS TABLE   
            // if($request->filled('contact_no_ext')){
            //     $contact_numbers = [
            //         ['electrician_id' => $electrician_id, 'contact_no' => $request->contact_no],
            //         ['electrician_id' => $electrician_id, 'contact_no' => $request->contact_no_ext],
            //     ];
            // }  
            // else{
            //     $contact_numbers = [
            //         ['electrician_id' => $electrician_id, 'contact_no' => $request->contact_no]
            //     ];
            // }
            
            // DB::table('barangay_electrician_contact_numbers')->insert($contact_numbers);

            //  INSERT ADDRESS   
            DB::table('barangay_electrician_addresses')->insert(
                [
                    'electrician_id' => $electrician_id, 
                    'house_no_sitio_purok_street' => $request->house_no_zone_purok_sitio,
                    'district_id' => $request->district,
                    'municipality_id' => $request->municipality,
                    'barangay_id' => $request->barangay,
                    'postal_code' => $request->postal_code,
                    'created_at' => $now
                ]
            );
            // dd($request);
            //  INSERT ACCOUNT DETAILS  
            DB::table('barangay_electrician_account_details')->insert(
                [
                    'electrician_id' => $electrician_id, 
                    'membership_or' => $request->membership_or,
                    'membership_date' => $request->membership_date,
                    'account_no' => $request->electric_service_details,
                    'account_name' => 'NONE',
                    'created_at' => $now
                ]
            );
        });

        return redirect(route('electrician.index'))->withSuccess('Record Successfully Created!');

        // dd($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Electrician $electrician)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Electrician $electrician)
    {
        $data = Electrician::find($electrician);
        $account_number = $data[0]->electrician_accounts[0]->account_no;

        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();

        $account = DB::connection('sqlSrvBilling')
        ->table('Consumers Table')
        ->where('Accnt No', $account_number)
        ->select('Accnt No as id', 'Name', 'Address')
        ->get();

        return view('electrician.edit')->with(compact('districts','data','account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Electrician $electrician)
    {
        // validate requests
        $this->validate($request, [
            // 'control_no' => ['required', 'string', 'max:255', 'unique:lifelines,control_no'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'civil_status' => ['required'],
            'date_of_birth' => ['required', 'string', 'max:255'],
            'type_of_id' => ['required'],
            'valid_id_no' => ['required', 'string', 'max:255'],
            'contact_no' => ['nullable', 'regex:/^((09))[0-9]{9}/', 'digits:11'],
            'application_type' => ['required'],
            'application_status' => ['required'],
            'district' => ['required'],
            'municipality' => ['required'],
            // 'postal_code' => ['required'],
            // 'membership_or' => ['required'],
            // 'membership_date' => ['required'],
            // 'electric_service_details' => ['required'],
        ]);
        // dd($request);
        $now = DB::raw('CURRENT_TIMESTAMP');
        DB::transaction(function () use ($request, $electrician, $now) {

            // INSERT ELECTRICIAN DETAILS
            // $electrician_id = DB::table('barangay_electricians')->where('id', $electrician->id)->update(
            //     array(
            //         // 'control_number' => $control_id,
            //         'first_name' => $request->first_name,
            //         'middle_name' => $request->middle_name,
            //         'last_name' => $request->last_name,
            //         'name_ext' => $request->name_ext,
            //         'sex' => $request->sex,
            //         'civil_status' => $request->civil_status,
            //         'date_of_birth' => $request->date_of_birth,
            //         'email_address' => $request->email_address,
            //         'fb_account' => $request->facebook_account,
            //         'spouse_first_name' => $request->spouse_first_name,
            //         'spouse_middle_name' => $request->spouse_middle_name,
            //         'spouse_last_name' => $request->spouse_last_name,
            //         'valid_id_type' => $request->type_of_id,
            //         'valid_id_number' => $request->valid_id_no,
            //         'application_type' => $request->application_type,
            //         'application_status' => $request->application_status,
                    
            //         'tesda_course_title' => $request->exists('course_title') ? $request->course_title : null ,
            //         'tesda_name_of_school' => $request->exists('name_of_school') ? $request->name_of_school : null ,
            //         'tesda_national_certificate_no' => $request->exists('certificate_no') ? $request->certificate_no : null ,
            //         'tesda_date_issued' => $request->exists('tesda_date_issued') ? $request->tesda_date_issued : null ,
            //         'tesda_valid_until_date' => $request->exists('tesda_validity') ? $request->tesda_validity : null ,

            //         'rme_license_no' => $request->exists('rme_license_no') ? $request->rme_license_no : null ,
            //         'rme_issued_on' => $request->exists('rme_license_issued_on') ? $request->rme_license_issued_on : null ,
            //         'rme_issued_at' => $request->exists('rme_license_issued_at') ? $request->rme_license_issued_at : null ,
            //         'rme_valid_until' => $request->exists('rme_validity') ? $request->rme_validity : null ,

            //         'ree_license_no' => $request->exists('ree_license_no') ? $request->ree_license_no : null ,
            //         'ree_issued_on' => $request->exists('ree_license_issued_on') ? $request->ree_license_issued_on : null ,
            //         'ree_issued_at' => $request->exists('ree_license_issued_at') ? $request->ree_license_issued_at : null ,
            //         'ree_valid_until' => $request->exists('ree_validity') ? $request->ree_validity : null ,
            //         'remarks' => $request->remarks,
            //         'application_status_remarks' => $request->application_remarks,
            //         'date_of_application' => $request->date_of_application,
            //         'created_by' => Auth::id(),

            //         'updated_at' => $now
            //     )
            // );

            // Retrieve the model instance by ID
            $electrician = Electrician::findOrFail($electrician->id);

            // dd($electrician);
            // Update the fields
            $electrician->update([
                // 'control_number' => $control_id, // Uncomment if required
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'name_ext' => $request->name_ext,
                'sex' => $request->sex,
                'civil_status' => $request->civil_status,
                'date_of_birth' => $request->date_of_birth,
                'email_address' => $request->email_address,
                'fb_account' => $request->facebook_account,
                'spouse_first_name' => $request->spouse_first_name,
                'spouse_middle_name' => $request->spouse_middle_name,
                'spouse_last_name' => $request->spouse_last_name,
                'valid_id_type' => $request->type_of_id,
                'valid_id_number' => $request->valid_id_no,
                'application_type' => $request->application_type,
                'application_status' => $request->application_status,

                'tesda_course_title' => $request->has('course_title') ? $request->course_title : null,
                'tesda_name_of_school' => $request->has('name_of_school') ? $request->name_of_school : null,
                'tesda_national_certificate_no' => $request->has('certificate_no') ? $request->certificate_no : null,
                'tesda_date_issued' => $request->has('tesda_date_issued') ? $request->tesda_date_issued : null,
                'tesda_valid_until_date' => $request->has('tesda_validity') ? $request->tesda_validity : null,

                'rme_license_no' => $request->has('rme_license_no') ? $request->rme_license_no : null,
                'rme_issued_on' => $request->has('rme_license_issued_on') ? $request->rme_license_issued_on : null,
                'rme_issued_at' => $request->has('rme_license_issued_at') ? $request->rme_license_issued_at : null,
                'rme_valid_until' => $request->has('rme_validity') ? $request->rme_validity : null,

                'ree_license_no' => $request->has('ree_license_no') ? $request->ree_license_no : null,
                'ree_issued_on' => $request->has('ree_license_issued_on') ? $request->ree_license_issued_on : null,
                'ree_issued_at' => $request->has('ree_license_issued_at') ? $request->ree_license_issued_at : null,
                'ree_valid_until' => $request->has('ree_validity') ? $request->ree_validity : null,

                'primary_contact_no' => $request->contact_no,
                'secondary_contact_no' => $request->contact_no_ext,

                'remarks' => $request->remarks,
                'application_status_remarks' => $request->application_remarks,
                'date_of_application' => $request->date_of_application,
                'created_by' => Auth::id(),

                'updated_at' => now(), // You can use `now()` directly in Laravel
            ]);

            //  INSERT EDUCATIONAL BACKGROUND TABLE     
            $educ_background = $request->educationalBackground;
            // dd($electrician->id);
            if($educ_background){

                // delete existing record
                DB::table('barangay_electrician_educational_backgrounds')
                ->where('electrician_id', $electrician->id)
                ->delete();

                // insert new record
                foreach ($educ_background as $key => $value) {
                    ElectricianEducationalBackground::create([
                        'electrician_id' => $electrician->id,
                        'educational_stage' => $value['educational_stage'],
                        'name_of_school' => $value['name_of_school'],
                        'degree_recieved' => $value['degree_recieved'],
                        'year_graduated' => $value['year_graduated'],
                    ]);
                }
            } else{
                // delete existing record
                DB::table('barangay_electrician_educational_backgrounds')
                ->where('electrician_id', $electrician->id)
                ->delete();
            }

            //  INSERT ADDRESS   
            DB::table('barangay_electrician_addresses')->where('electrician_id', $electrician->id)->update(
                [
                    // 'electrician_id' => $electrician_id, 
                    'house_no_sitio_purok_street' => $request->house_no_zone_purok_sitio,
                    'district_id' => $request->district,
                    'municipality_id' => $request->municipality,
                    'barangay_id' => $request->barangay,
                    'postal_code' => $request->postal_code,
                    'updated_at' => $now
                ]
            );
            // dd($request);
            //  INSERT ACCOUNT DETAILS  
            DB::table('barangay_electrician_account_details')->where('electrician_id', $electrician->id)->update(
                [
                    'electrician_id' => $electrician->id, 
                    'membership_or' => $request->membership_or,
                    'membership_date' => $request->membership_date,
                    'account_no' => $request->electric_service_details,
                    'account_name' => 'NONE',
                    'created_at' => $now
                ]
            );
        });

        return redirect(route('electrician.index'))->withSuccess('Record Successfully Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Electrician $electrician)
    {
        $member = Electrician::find($electrician->id);
        if($member->electrician_complaints->count() == 0){
            $member->delete();
            return redirect(route('electrician.index'))->withSuccess('Record Successfully deleted!');
        }
        else{
            return redirect(route('electrician.index'))->withError("Can't delete the record. This record has existing complaints.");
        }
    }

    public function electricianComplaintIndex()
    {
        $data = ElectricianComplaint::with('electrician')->orderBy('id', 'asc')->paginate(10);
        return view('electrician.complaint.index')->with(compact('data'));
    }

    public function electricianComplaintCreate()
    {
        $electricians = Electrician::orderBy('last_name', 'asc')->where('application_status', 2)->get();
        $control_id = Helper::IDGeneratorQueryBuilder(DB::table('barangay_electrician_complaints'), 'control_number', 4, date('Y'));
        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();
        return view('electrician.complaint.create')->with(compact('electricians','control_id', 'districts'));
    }

    public function electricianComplaintStore(Request $request)
    {
        $this->validate($request, [
            // 'control_number' => ['required', 'string', 'max:255', 'unique:barangay_electrician_complaints,control_number'],
            'complainant' => ['required', 'string'],
            'electrician' => ['required', 'string'],
            'nature_of_complaint' => ['required'],
            'act_of_misconduct' => ['required'],
            'complaint_date' => ['required'],
            'sanction_type' => ['required'],
            'status_of_complaint' => ['required'],
            'attached_file' => 'mimes:pdf|max:10000',
            'district' => ['required'],
            'municipality' => ['required'],
            'barangay' => ['required'],
        ]);

        $attached_file = $request->file('attached_file');

        if($attached_file){

            $file = $request->file('attached_file');
            $filename = time() . '.' . $request->file('attached_file')->extension();
            $filePath = public_path() . '/pdf/complaints/';
            $file->move($filePath, $filename);

            $path = 'pdf/complaints/'.$filename;

        }
        else{
            $path = null;
        }

        $now = DB::raw('CURRENT_TIMESTAMP');

        $year = date('Y'); // Assuming you already have the year

        $control_id = Helper::IDGeneratorQueryBuilder(DB::table('barangay_electrician_complaints'), 'control_number', 4, $year); /** Generate control no */

        // dd($path);
        // Use $control_id to generate the new control number

        // dd($request);
        DB::transaction(function () use ($request, $now, $control_id, $path) {

            // INSERT ELECTRICIAN COMPLAINT
            ElectricianComplaint::create([
                'control_number' => $control_id,
                'date' => $request->complaint_date,
                'complainant_name' => $request->complainant,
                'electrician_id' => $request->electrician,
                'act_of_misconduct' => $request->act_of_misconduct,
                'remarks' => $request->remarks,
                'nature_of_complaint' => $request->exists('other_complaint') ? null : $request->nature_of_complaint,
                'other_nature_of_complaint' => $request->exists('other_complaint') ? $request->other_complaint : null,
                'sanction_type' => $request->sanction_type,
                'revocation_date' => $request->exists('revocation_date') ? $request->revocation_date : null,
                'date_of_suspension_from' => $request->exists('suspension_from') ? $request->suspension_from : null,
                'date_of_suspension_to' => $request->exists('suspension_to') ? $request->suspension_to : null,
                'status_of_complaint' => $request->status_of_complaint,
                'status_explanation' => $request->exists('status_explanation') ? $request->status_explanation : null,
                'sanction_remarks' => $request->sanction_remarks,
                'file_path' => $path,
                'complainant_contact_no' => $request->contact_no,
                'complainant_district_id' => $request->district,
                'complainant_municipality_id' => $request->municipality,
                'complainant_barangay_id' => $request->barangay,
            ]);
        });


        return redirect(route('electricianComplaintIndex'))->withSuccess('Record Successfully created!');
    }

    public function electricianComplaintEdit(ElectricianComplaint $electricianComplaint, $id)
    {
        $complaint = ElectricianComplaint::find($id);
        $electricians = Electrician::orderBy('id', 'desc')->where('application_status', 2)->get();
        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();
        // dd($complaint->other_nature_of_complaint);
        return view('electrician.complaint.edit')->with(compact('complaint','electricians', 'districts'));
        // return redirect(route('electricianComplaintIndex'))->withSuccess('Record Successfully updated!');
    }

    public function electricianComplaintUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'complainant' => ['required', 'string'],
            'electrician' => ['required', 'string'],
            'nature_of_complaint' => ['required'],
            'act_of_misconduct' => ['required'],
            'complaint_date' => ['required'],
            'sanction_type' => ['required'],
            'status_of_complaint' => ['required'],
            'attached_file' => 'mimes:pdf|max:10000',
            'district' => ['required'],
            'municipality' => ['required'],
            'barangay' => ['required'],
        ]);
        
        if($request->hasFile('attached_file')){

            $fileToDelete = $request->old_file_path;

            if ($fileToDelete && File::exists(public_path($fileToDelete))) {
                // Delete the file
                File::delete(public_path($fileToDelete));
            }

            $file = $request->file('attached_file');
            $filename = time() . '.' . $request->file('attached_file')->extension();
            $filePath = public_path() . '/pdf/complaints/';
            $file->move($filePath, $filename);

            $path = 'pdf/complaints/'.$filename;
        }
        else{
            $path = $request->old_file_path;
        }

        DB::transaction(function () use ($request, $id, $path) {

            // INSERT ELECTRICIAN COMPLAINT
            DB::table('barangay_electrician_complaints')->where('id', $id)->update(
                array(
                    'date' => $request->complaint_date,
                    'complainant_name' => $request->complainant,
                    'electrician_id' => $request->electrician,
                    'act_of_misconduct' => $request->act_of_misconduct,
                    'remarks' => $request->remarks,
                    'nature_of_complaint' => $request->exists('other_complaint') ? null : $request->nature_of_complaint ,
                    'other_nature_of_complaint' => $request->exists('other_complaint') ? $request->other_complaint : null ,

                    'sanction_type' => $request->sanction_type,
                    'revocation_date' => $request->exists('revocation_date') ? $request->revocation_date : null ,
                    'date_of_suspension_from' => $request->exists('suspension_from') ? $request->suspension_from : null ,
                    'date_of_suspension_to' => $request->exists('suspension_to') ? $request->suspension_to : null ,

                    'status_of_complaint' => $request->status_of_complaint,
                    'status_explanation' => $request->exists('status_explanation') ? $request->status_explanation : null ,

                    'sanction_remarks' => $request->sanction_remarks,
                    'file_path' => $path,
                    'complainant_contact_no' => $request->contact_no,
                    'complainant_district_id' => $request->district,
                    'complainant_municipality_id' => $request->municipality,
                    'complainant_barangay_id' => $request->barangay,

                    'created_at' => DB::raw('CURRENT_TIMESTAMP')
                )
            );
        });
        // $complaint = ElectricianComplaint::find($id);
        // $electricians = Electrician::orderBy('id', 'desc')->get();
        // dd($complaint->other_nature_of_complaint);
        return redirect(route('electricianComplaintIndex'))->withSuccess('Record Successfully updated!');
        // return redirect(route('electricianComplaintIndex'))->withSuccess('Record Successfully updated!');
    }

    public function electricianComplaintDelete(ElectricianComplaint $electricianComplaint, $id)
    {
        $complaint = ElectricianComplaint::find($id);
        $complaint->delete();
        return redirect(route('electricianComplaintIndex'))->withSuccess('Record Successfully deleted!');
    }

    public function electricianComplaintView(ElectricianComplaint $electricianComplaint, $id)
    {
        $electrician = Electrician::find($id);
        return view('electrician.complaint_count')->with(compact('electrician'));
    }

    public function fetchElectricianApplication(Request $request)
    {
        $data = Electrician::orderBy('id','desc')->paginate(10);
        if($request->ajax()){
            
            $data = Electrician::where('control_number', 'LIKE', '%'.$request->control_number.'%')
            ->where('first_name', 'LIKE', '%'.$request->fname.'%')
            ->where('last_name', 'LIKE', '%'.$request->lname.'%')
            ->paginate(10);
            
            return view('electrician.search')->with(compact('data'))->render();
        }

        return view('electrician.index')->with(compact('data'));
    }
    
    public function electricianActivityIndex()
    {
        $activities = DB::table('electrician_activities')->orderBy('id', 'desc')->paginate(10);
        return view('electrician.activity.index')->with(compact('activities'));
    }

    public function electricianActivityCreate()
    {
        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();
        return view('electrician.activity.create')->with(compact('districts'));
    }

    public function fetchElectricians(Request $request)
    {
            $search = $request->search;
            
            if($search == ''){
                $electricians = Electrician::orderBy('last_name','desc');
            }else{
                $electricians = Electrician::where('last_name', $search)->orderBy('last_name','desc');
            }
            // dd($electricians);
            $data = $electricians->paginate(10, ['*'], 'page', $request->page);
            return response()->json($data); 
    }

    public function electricianActivityStore(Request $request)
    {
        // validate requests
        $this->validate($request, [
            'name_of_activity' => ['required', 'string', 'max:255'],
            'conducted_by' => ['required', 'string', 'max:255'],
            'date_of_activity' => ['required'],
            'time_conducted' => ['required'],
            'venue' => ['required'],
            'participants' => ['required'],
            'attendees' => ['required'],
        ]);
        $now = DB::raw('CURRENT_TIMESTAMP');
        $attendees = $request->attendees;
        try {
            // Start a database transaction
            DB::beginTransaction();

            // INSERT ELECTRICIAN COMPLAINT
            $activityId = DB::table('electrician_activities')->insertGetId(
                array(
                    'activity_name' => $request->name_of_activity,
                    'facilited_by' => $request->conducted_by,
                    'date_conducted' => $request->date_of_activity,
                    'time_conducted' => $request->time_conducted,
                    'venue' => $request->venue,
                    'target_participants' => $request->participants,
                    'status' => 0,
                    'created_at' => $now
                )
            );
            
            foreach ($attendees as $key => $value) {
                DB::table('electrician_attendance')->insert(
                    array(
                        'electrician_id' => $value,
                        'activity_id' => $activityId,
                    )
                );
            }
            DB::commit();
            return redirect(route('electricianActivityIndex'))->withSuccess('Record successfully created!');
            
        } catch (\Throwable $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollBack();

            // You can log the exception for further investigation if needed
            \Log::error($e);

            // Return an error response or redirect to an error page
            return response()->json(['error' => $e], 500);

        }
    }

    public function electricianActivityEdit($id)
    {
        $activity = DB::table('electrician_activities')->where('id', $id)->first();
        $electricians = DB::table('electrician_attendance')->where('activity_id', $id)->pluck('electrician_id');
        // $electricians = Electrician::whereIn('id', $electricians)
        // ->orderBy('last_name','desc')
        // ->select('id','first_name','last_name')
        // ->get()
        // ->map(function ($electrician) {
        //     return $electrician->getAttributes();
        // });

        $electricians = Electrician::whereIn('id', $electricians)->orderBy('last_name','desc')->paginate(10);
        // return response()->json($electricians); 

    //     $data = Electrician::whereIn('id', $electricians)
    // ->orderBy('last_name', 'desc')
    // ->select('id', 'first_name', 'last_name')
    // ->get();


        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();
        //  dd($electricians);
        //  return($electricians);
        return view('electrician.activity.edit')->with(compact('activity','districts','electricians'));
    }
    
}
