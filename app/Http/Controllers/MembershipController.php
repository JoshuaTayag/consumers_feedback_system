<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DateTime;
use App\Models\Premembership;
use App\Models\Membership;
use Illuminate\Validation\Rule;

class MembershipController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:membership-list|membership-create|membership-edit|membership-delete', ['only' => ['index','store']]);
         $this->middleware('permission:membership-create', ['only' => ['create','store']]);
         $this->middleware('permission:membership-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:membership-delete', ['only' => ['destroy']]);

         $this->middleware('permission:pre-membership-list|pre-membership-create|pre-membership-edit|pre-membership-delete', ['only' => ['preMembershipIndex','preMembershipStore']]);
         $this->middleware('permission:pre-membership-create', ['only' => ['preMembershipCreate','preMembershipStore']]);
         $this->middleware('permission:pre-membership-edit', ['only' => ['preMembershipEdit','preMembershipUpdate']]);
         $this->middleware('permission:pre-membership-delete', ['only' => ['preMembershipDestroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $members = Membership::orderBy('id', 'desc')->paginate(10);
        
        if($request->ajax()){
            // $users = Membership::query()
            //             ->when($request->seach_term, function($q)use($request){
            //                 $q->where('id', 'like', '%'.$request->seach_term.'%')
            //                 ->orWhere('name', 'like', '%'.$request->seach_term.'%')
            //                 ->orWhere('email', 'like', '%'.$request->seach_term.'%');
            //             })
            //             ->when($request->status, function($q)use($request){
            //                 $q->where('status',$request->status);
            //             })
            //             ->paginate(10);

            $members = Membership::where('OR No', 'LIKE', '%'.$request->or_number.'%')
            ->where('First Name', 'LIKE', '%'.$request->fname.'%')
            ->where('Last Name', 'LIKE', '%'.$request->lname.'%')
            ->paginate(10)
            ->get();

            return view('membership.membership_index')->with(compact('members'));
        }

        // dd($members);
        return view('membership.membership_index')->with(compact('members'));
    }

    public function preMembershipIndex()
    {
        $pre_members = Premembership::with('district', 'municipality', 'barangay')->paginate(10);
        
        // dd($members);
        return view('membership.pre_membership_index')->with(compact('pre_members'));
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
        return view('membership.membership_create')->with(compact('districts'));
    }

    public function preMembershipCreate()
    {
        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();

        // dd($districts);
        return view('membership.pre_membership_create')->with(compact('districts'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate requests
        $this->validate($request, [
            'or_no' => ['required', 'string', 'max:255', 'unique:sqlSrvMembership.Consumer Masterdatabase Table,OR No'],
            'or_no_issued' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'string', 'max:255'],
            'contact_no' => ['required', 'regex:/^((09))[0-9]{9}/', 'digits:11'],
            'district' => ['required', 'string', 'max:255'],
            'municipality' => ['required', 'string', 'max:255'],
            'barangay' => ['string', 'max:255'],
            'membership_status' => ['string', 'max:255'],
            'membership_type' => ['string', 'max:255'],
        ]);

        // Insert Record
        Membership::create([
            "OR No" => $request->or_no,
            "OR No Issued" => $request->or_no_issued,
            "First Name" => $request->first_name,
            "Middle Name" => $request->middle_name,
            "Last Name" => $request->last_name,
            "Joint Name" => $request->spouse,
            "Joint" => $request->exists('joint') ? 1 : 0,
            "DateBirth" => $request->date_of_birth,
            "Celphone" => $request->contact_no,
            "District" => $request->district,
            "Municipality" => $request->municipality,
            "Brgy" => $request->barangay,
            "Sitio" => $request->sitio,
            "Status" => $request->membership_status,
            "MemType" => $request->membership_type,
            "Remarks" => $request->remarks,
        ]);

        return redirect(route('membership.index'))->withSuccess('Record Successfully Created!');

    }



    public function preMembershipStore(Request $request)
    {
        // validate requests
        $this->validate($request, [
            'first_name' => ['required', 
                Rule::unique('sqlSrvMembership.pre_membership')->where(function ($query) use($request) {
                    return $query->where('first_name', $request->first_name)
                    ->where('last_name', $request->last_name);
                }),
            ],
            // 'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            // 'date_of_birth' => ['required', 'string', 'max:255'],
            'contact_no' => ['regex:/^((09))[0-9]{9}/', 'digits:11'],
            'place_conducted' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'municipality' => ['required', 'string', 'max:255'],
            'barangay' => ['string', 'max:255'],
            'date_conducted' => ['required', 'string'],
            'time_conducted' => ['required', 'string'],
            'pms_conductor' => ['required', 'string'],
        ]);

        // Insert Record
        Premembership::create([
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
            "spouse" => $request->spouse,
            "joint" => $request->exists('joint') ? 1 : 0,
            "single" => $request->exists('joint') ? 0 : 1,
            "date_of_birth" => $request->date_of_birth ? $request->date_of_birth : "2023-07-14",
            "contact_no" => $request->contact_no,
            "district_id" => $request->district,
            "municipality_id" => $request->municipality,
            "barangay_id" => $request->barangay,
            "sitio" => $request->sitio,
            "date_and_time_conducted" => $request->date_conducted.' '.$request->time_conducted,
            "pms_conductor" => $request->pms_conductor,
            "place_conducted" => $request->place_conducted,
            "juridical" => $request->exists('juridical') ? 1 : 0,
        ]);

        return redirect(route('pre_membership_index'))->withSuccess('Record Successfully Created!');

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
        $member = Membership::find($id);
        // dd($member);
        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();
        
        // dd($members);
        return view('membership.membership_edit')->with(compact('member', 'districts'));
    }

    public function preMembershipEdit(string $id)
    {
        $pre_member = Premembership::with('district', 'municipality', 'barangay')->find($id);
        // dd($pre_member);
        $districts = DB::connection('sqlSrvMembership')
        ->table('districts')
        ->select('*')
        ->get();
        
        // dd($members);
        return view('membership.pre_membership_edit')->with(compact('pre_member', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validate requests
        $this->validate($request, [
            'or_no' => ['required', 'string', 'max:255', 'unique:sqlSrvMembership.Consumer Masterdatabase Table,OR No,'.$id],     
            'or_no_issued' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'string', 'max:255'],
            'contact_no' => ['required', 'regex:/^((09))[0-9]{9}/', 'digits:11'],
            'district' => ['required', 'string', 'max:255'],
            'municipality' => ['required', 'string', 'max:255'],
            'barangay' => ['string', 'max:255'],
            'membership_status' => ['string', 'max:255'],
            'membership_type' => ['string', 'max:255'],
        ]);

        // Update Record
        $member = Membership::find($id);
        $member->{'OR No'} = $request->or_no;
        $member->{'OR No Issued'} = $request->or_no_issued;
        $member->{'First Name'} = $request->first_name;
        $member->{'Middle Name'} = $request->middle_name;
        $member->{'Last Name'} = $request->last_name;
        $member->{'Joint Name'} = $request->spouse;
        $member->{'Joint'} = $request->exists('joint') ? 1 : 0;
        $member->{'DateBirth'} = $request->date_of_birth;
        $member->{'Celphone'} = $request->contact_no;
        $member->{'District'} = $request->district;
        $member->{'Municipality'} = $request->municipality;
        $member->{'Brgy'} = $request->barangay;
        $member->{'Sitio'} = $request->sitio;
        $member->{'Status'} = $request->membership_status;
        $member->{'MemType'} = $request->membership_type;
        $member->{'Remarks'} = $request->remarks;
        $member->save();

        return redirect(route('membership.index'))->withSuccess('Record Successfully Updated!');
    }

    public function preMembershipUpdate(Request $request, string $id)
    {

        // validate requests
        $this->validate($request, [
            // 'first_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 
                Rule::unique('sqlSrvMembership.pre_membership')->ignore($id)->where(function ($query) use($request) {
                    return $query->where('first_name', $request->first_name)
                    ->where('last_name', $request->last_name);
                }),
            ],
            // 'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            // 'date_of_birth' => ['required', 'string', 'max:255'],
            'contact_no' => ['regex:/^((09))[0-9]{9}/','digits:11'],
            'place_conducted' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'municipality' => ['required', 'string', 'max:255'],
            'barangay' => ['string', 'max:255'],
            'date_conducted' => ['required', 'string'],
            'time_conducted' => ['required', 'string'],
            'pms_conductor' => ['required', 'string'],
        ]);


        // dd($request);

        // Update Record
        $pre_member = Premembership::find($id);
        $pre_member->first_name = $request->first_name;
        $pre_member->middle_name = $request->middle_name;
        $pre_member->last_name = $request->last_name;
        $pre_member->spouse = $request->spouse;
        $pre_member->joint = $request->exists('joint') ? 1 : 0;
        $pre_member->single = $request->exists('single') ? 0 : 1;
        $pre_member->date_of_birth = $request->date_of_birth ? $request->date_of_birth : "2023-07-14";
        $pre_member->contact_no = $request->contact_no;
        $pre_member->district_id = $request->district;
        $pre_member->municipality_id = $request->municipality;
        $pre_member->barangay_id = $request->barangay;
        $pre_member->sitio = $request->sitio;
        $pre_member->date_and_time_conducted = $request->date_conducted.' '.$request->time_conducted;
        $pre_member->pms_conductor = $request->pms_conductor;
        $pre_member->place_conducted = $request->place_conducted;
        $pre_member->juridical = $request->exists('juridical') ? 1 : 0;
        $pre_member->save();
        
        return redirect(route('pre_membership_index'))->withSuccess('Record Successfully Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // dd($id);
        $member = Membership::find($id);
        $member->delete();

        return redirect(route('membership.index'))->withSuccess('Record Successfully deleted!');
    }

    public function preMembershipDestroy(string $id)
    {
        // dd($id);
        $member = Premembership::find($id);
        $member->delete();

        return redirect(route('pre_membership_index'))->withSuccess('Record Successfully deleted!');
    }

    public function fetchPreMembers(Request $request)
    {
        $pre_members = Premembership::orderBy('id', 'desc')->paginate(10);
        
        if($request->ajax()){
            
            $pre_members = Premembership::where('pms_conductor', 'LIKE', '%'.$request->pms_conductor.'%')
            ->where('first_name', 'LIKE', '%'.$request->fname.'%')
            ->where('last_name', 'LIKE', '%'.$request->lname.'%')
            ->paginate(10);
            
            return view('membership.pre_membership_search')->with(compact('pre_members'))->render();
        }

        return view('membership.pre_membership_index')->with(compact('pre_members'));
    }

    public function fetchMembers(Request $request)
    {
        $members = Membership::orderBy('id', 'desc')->paginate(10);
        
        if($request->ajax()){
            
            $members = Membership::where('OR No', 'LIKE', '%'.$request->or_number.'%')
            ->where('First Name', 'LIKE', '%'.$request->fname.'%')
            ->where('Last Name', 'LIKE', '%'.$request->lname.'%')
            ->paginate(10);
            
            return view('membership.membership_search')->with(compact('members'))->render();
        }

        return view('membership.membership_index')->with(compact('members'));
    }

    public function onlineSeminarQuestionare(Request $request)
    {
        return view('consumer.index');
    }


}
