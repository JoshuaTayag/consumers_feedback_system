<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agmm;
use DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use PDF;

class AgmmController extends Controller
{
    public function agmmRegister(Request $request)
    {
        $account = $this->validateAccount($request->account_no);
        
        // check if account_no exist in consumers table
        if ($account['status_message'] == "success") {
            // Validate incoming request data
            $validatedData = $request->validate([
                'account_no' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'contact_no' => 'nullable|string|max:20',
                'membership_or' => 'nullable|string|max:20',
            ]);

            try {

                // Check if account_no exist in registration table
                // $accountExists = Agmm::where('account_no', $request->account_no)->exists();
                $accountExists = DB::table('agmms')->where('account_no', $request->account_no)->exists();

                // return response()->json($account['account']->membership_or);
                if ($accountExists) {
                    return response()->json(['message' => 'Account Number is already registered', 'status_message' => 'error'], 409);
                }
                else{
                    // Override membership_or if it exists in the request
                    $validatedData['membership_or'] = $account['account']->membership_or;

                    // Generate UUID
                    $uuid = Uuid::uuid4()->toString();

                    // Add UUID and registration type to validated data
                    $validatedData['qr_code_value'] = $uuid;
                    $validatedData['registration_type'] = 'ONLINE-API';
                    
                    // Get the first two digit of the account_no
                    $first_two_digits = substr($validatedData['account_no'], 0, 2);
                
                    // Get reference allowance from the table
                    $allowance = DB::table('agmm_ref_allowance')
                    ->where('area_code', $first_two_digits)
                    ->value('allowance');
                    $validatedData['transpo_allowance'] = $allowance ? $allowance : 0;
                    $validatedData['allowance_status'] = false;

                    // remove white space in membership OR
                    $validatedData['membership_or'] = str_replace(' ', '', $validatedData['membership_or']);

                    // Store the validated data in the database
                    // $registration = Agmm::create($validatedData);

                    $registration = DB::table('agmms')->insert(
                        array(
                            'account_no' => $request->account_no,
                            'first_name' => $request->first_name,
                            'middle_name' => $request->middle_name,
                            'last_name' => $request->last_name,
                            'contact_no' => $request->contact_no,
                            'membership_or' => $validatedData['membership_or'],
                            'registration_type' => $request->type,
                            'qr_code_value' => $uuid,
                            'transpo_allowance' => $validatedData['transpo_allowance'],
                            'allowance_status' => false,
                            'created_at' => now()
                        )
                    );

                    $insertedData = DB::table('agmms')
                    ->where('qr_code_value', $uuid)
                    ->first();

                    if ($insertedData) {
                        // Convert the result to a collection to use makeHidden method
                        $insertedDataCollection = collect($insertedData);
                    
                        // Exclude the timestamps
                        $dataWithoutTimestamps = $insertedDataCollection->except(['created_at', 'updated_at']);
                    
                        // If you need it as an array
                        $dataWithoutTimestampsArray = $dataWithoutTimestamps->toArray();

                    }

                    // Exclude timestamps from the registration collection
                    // $registrationWithoutTimestamps = $registration->makeHidden(['created_at', 'updated_at']);

                    // Return a response
                    return response()->json(['message' => 'User successfully Registered', 'registration' => $dataWithoutTimestampsArray, 'status_message' => 'success'], 201);
                }

            } catch (\Exception $e) {
                // Handle any unexpected exceptions
                // dd($e);
                return response()->json(['message' => 'Failed to process the request', 'status_message' => 'error'], 500);
            }
    
        } else {
            return response()->json($account);
        }
        
        
    }

    public function agmmRegistration(){
        return view('agmm.agmm_onsite_registration');
    }

    // public function agmmRegisterPost(Request $request){
    //     return view('agmm.agmm_onsite_registration');
    // }

    public function agmmRegisterPost(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'account_no' => 'required',
            'contact_no' => ['nullable', 'regex:/^((09))[0-9]{9}/', 'digits:11']
        ]);
        $account_no = $request->account_no;
        // Modify account_no to remove hyphens
        $request->merge(['account_no' => str_replace('-', '', $request->account_no)]);

        // no data for membership
        $request->merge(['membership_or' => '']);
        $request->merge(['type' => 'ONLINE-PRE-REGISTRATION']);

        // Make a POST request to another API
        try {
            // dd($request);
            $response = $this->agmmRegister($request);
            // Get the response body
            $responseData = $response->getData(true);
            // dd($response);
            // Decode the JSON response
        // $responseData = $response;
        // dd($responseData['status_message']);
            // Check the status message received from the API
            if ($responseData['status_message'] == 'success') {
                $qr_data = [
                    'AccountNumberWithoutHypens' => $request->account_no,
                ];
                // dd($qr_data);
                // return view('new_homepage.agmm_registration')->with('successMessage', $responseData['message']);
                return redirect()->back()->with(['successMessage' => $responseData['message'], 'data' => $qr_data]);
            } else {
                // return view('new_homepage.agmm_registration')->with('errorMessage', $responseData['message']);
                
                // dd($qr_data);
                
                return redirect()->back()->with(['errorMessage' => $responseData['message']])->withInput();
            }
        } catch (RequestException $e) {
            // Log or handle the exception
            return $e->getMessage();
        }
    }

    public function validateAccount($id)
    {
        // Retrieve the account from the database
        $account = DB::connection('sqlSrvBilling')
            ->table('Consumers Table')
            ->where('Accnt No', $id)
            ->select('Accnt No as id', 'Name', 'Address', 'OR No as membership_or')
            ->first(); // Using first() to get a single record

        // Check if the account exists
        if ($account) {
            return ['message' => 'Account exists', 'account' => $account, 'status_message' => 'success'];
        } else {
            return ['message' => 'Account Number does not exist', 'status_message' => 'error'];
        }
    }

    public function getRegistration($id)
    {
        // Retrieve the account from the database
        // $registration = Agmm::where('account_no', $id)->get();
        $registration = DB::table('agmm_verified_accounts')->where('account_no', $id)->get();

        // Check if the account exists
        if ($registration->count() > 0) {
            // return ['message' => 'Account exists', 'account' => $account, 'status_message' => 'success'];
            return response()->json(['message' => 'Registration Exist', 'registration' => $registration, 'status_message' => 'success'], 201);
        } else {
            return response()->json(['message' => 'Registration does not exist', 'status_message' => 'error']);
        }
    }

    public function getRegistrationByQr($id)
    {
        // Retrieve the account from the database
        $registration = Agmm::where('qr_code_value', $id)->get();

        // Check if the account exists
        if ($registration->count() > 0) {
            // return ['message' => 'Account exists', 'account' => $account, 'status_message' => 'success'];
            return response()->json(['message' => 'Registration Exist', 'registration' => $registration, 'status_message' => 'success'], 201);
        } else {
            return response()->json(['message' => 'Registration does not exist', 'status_message' => 'error']);
        }
    }

    public function getAllowance($qr)
    {
        // Retrieve the account from the database
        $registration = DB::table('agmm_verified_accounts')->where('qr_code_value', $qr)->get();

        // Check if the account exists
        if ($registration->count() > 0 && $registration->value('registration_type') == "MCO") {
            // return ['message' => 'Account exists', 'account' => $account, 'status_message' => 'success'];
            return response()->json(['message' => 'Registration Exist', 'registration' => $registration, 'status_message' => 'success'], 201);
        } else {
            return response()->json(['message' => 'Registration does not exist', 'status_message' => 'error']);
        }
    }

    public function getVerifiedRegistration($account)
    {
        // Retrieve the account from the database
        $registration = DB::table('agmm_verified_accounts')->where('account_no', $account)->get();

        // Check if the account exists
        if ($registration->count() > 0) {
            // return ['message' => 'Account exists', 'account' => $account, 'status_message' => 'success'];
            return response()->json(['message' => 'Registration Exist', 'registration' => $registration, 'status_message' => 'success'], 201);
        } else {
            return response()->json(['message' => 'Registration does not exist', 'status_message' => 'error']);
        }
    }

    public function issueAllowance($qr)
    {
        // Retrieve the account from the database
        // $registration = Agmm::where('qr_code_value', $qr);
        // dd(Auth::id());
        $separatedArray = explode("|", $qr);

        // Retrieve the account from the database
        $registration = DB::table('agmm_verified_accounts')->where('qr_code_value', $separatedArray[0]);

        // get the memmership
        $membership = $registration->first()->membership_or;

        $claimed_registration_allowance = DB::table('agmm_verified_accounts')->where('membership_or', $membership)->where('allowance_status', true);

        // Check if the account exists
        if ($registration->count() > 0) {

            // Check if the status is already claimed
            if ($registration->first()->allowance_status == true || $claimed_registration_allowance->count() > 0) {
                return response()->json(['message' => 'Allowance already claimed!', 'status_message' => 'error'], 201);
            } else{
                $registration->update(['allowance_status' => true, 'remarks' => $separatedArray[1],  'claimed_by' => Auth::id()]);
                return response()->json(['message' => 'Allowance successfully claimed!', 'status_message' => 'success'], 201);
            }
            
        } else {
            return response()->json(['message' => 'Registration does not exist', 'status_message' => 'error']);
        }
    }

    public function agmmAccounts(Request $request){

        $total_verified_per_user = DB::table('agmm_verified_accounts')->where('verified_by', Auth::id())->count();
        $total_verified_mco_per_user = DB::table('agmm_verified_accounts')->where('verified_by', Auth::id())->where('registration_type', 'MCO')->count();
        $total_verified_guest_per_user = DB::table('agmm_verified_accounts')->where('verified_by', Auth::id())->where('registration_type', 'Guest')->count();

        $account_no = $request->input('account_no');
        $account_name = $request->input('account_name');

        $accountsQuery = DB::table('Consumers Table as ct')
        ->leftJoin('agmm_verified_accounts as ava', 'ct.Accnt No', '=', 'ava.account_no')
        ->select('ct.Accnt No as id', 'ct.Name', 'ct.Address', 'ct.OR No as membership_or')
        ->whereNull('ava.account_no') // Ensures only non-existing accounts are selected
        ->whereRaw('LEN(ct.[Accnt No]) = 10');

        // Add conditions based on the request input
        if ($request->filled('account_name')) {
            $accountsQuery->where('ct.Name', 'like', "%$account_name%");
        }

        if ($request->filled('account_no')) {
            $accountsQuery->where('ct.Accnt No', 'like', "%$account_no%");
        }

        // Paginate the results
        $accounts = $accountsQuery->paginate(5);

        // dd($accounts);
        
        return view('agmm.index')->with(compact('accounts', 'total_verified_per_user', 'total_verified_mco_per_user', 'total_verified_guest_per_user'));
    }

    public function agmmVerifyAccount($id, Request $request){
        // dd($request);
        try {
            $account = DB::connection('sqlSrvBilling')
            ->table('Consumers Table')
            ->where('Accnt No', $id)
            ->first();


            // Start a database transaction
            DB::beginTransaction();

            // generate UUID for QR Code
            $uuid = Uuid::uuid4()->toString();

            // Get the first two digit of the account_no
            $first_two_digits = substr($account->{'Accnt No'}, 0, 2);
                
            // Get reference allowance from the table
            $allowance = DB::table('agmm_ref_allowance')
            ->where('area_code', $first_two_digits)
            ->value('allowance');

            // INSERT ELECTRICIAN COMPLAINT
            $activityId = DB::table('agmm_verified_accounts')->insert(
                array(
                    'account_no' => $account->{'Accnt No'},
                    'name' => $account->{'Name'},
                    'contact_no' => $account->MobileNo,
                    'membership_or' => $account->{'OR No'},
                    'registration_type' => $request->swalValue,
                    'qr_code_value' => $uuid,
                    'transpo_allowance' => $allowance ? $allowance : 0,
                    'claimed_by' => "", 
                    'allowance_status' => false,
                    'verified_by' => Auth::id(),
                    'remarks' => "",
                    'created_at' => now()
                )
            );
            DB::commit();

            return redirect()->route('printRegistrationQR', $account->{'Accnt No'})->withSuccess('Successfully Register!');

            // Create PDF with QR Code
            // $pdf = PDF::loadView('agmm.agmm-qr-code-print', compact('qrCode'));
        
            // return redirect()->back()->withSuccess('Applications Successfully Uploaded!');
            
        } catch (\Throwable $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollBack();

            // Return an error response or redirect to an error page
            return $e;

        }
        return redirect()->back()->withSuccess('Applications Successfully Uploaded!');
    }

    public function printRegistrationQR($id){
        $details = DB::table('agmm_verified_accounts')->select('*')->where('account_no', $id)->first();
        if($details->verified_by != 0){
            $verifier = DB::table('users')->where('id', $details->verified_by)->value('name');
        }
        else{
            $verifier = "GUEST";
        }

        return view('agmm.agmm_qr_code_print')->with(compact('details','verifier'));
    }

    public function printRegistrationQRGuest($id){
        $details = DB::table('agmms')->select('*')->where('account_no', $id)->first();
        
        $verifier = "GUEST";
        
        return view('agmm.agmm_onsite_qr_code_print')->with(compact('details','verifier'));
    }

    public function scanQR(){
        $total_scanned_consumers = DB::table('agmm_verified_accounts')->where('claimed_by', Auth::id())->where('allowance_status', true)->count();
        $total_disbursed_money = DB::table('agmm_verified_accounts')->where('claimed_by', Auth::id())->where('allowance_status', true)->sum(DB::raw('CAST(transpo_allowance AS INT)'));
        return view('agmm.agmm_allowance_scanner')->with(compact('total_scanned_consumers', 'total_disbursed_money'));
    }

    public function agmmVerifyAccountFromPreReg($id, Request $request){
        try {
            $pre_registration = Agmm::where('qr_code_value', $id)->first();
            
            // $account = DB::connection('sqlSrvBilling')
            // ->table('Consumers Table')
            // ->where('Accnt No', $id)
            // ->first();


            // Start a database transaction
            DB::beginTransaction();


            // Get the first two digit of the account_no
            $first_two_digits = substr($pre_registration->account_no, 0, 2);
               
            // Get reference allowance from the table
            $allowance = DB::table('agmm_ref_allowance')
            ->where('area_code', $first_two_digits)
            ->value('allowance');

            // Generate new qr code for official registration
            $uuid = Uuid::uuid4()->toString();
		
            $verified_account_by_qr = DB::table('agmm_verified_accounts')->where('qr_code_value', $id)->get();
            $verified_account_account_no = DB::table('agmm_verified_accounts')->where('account_no', $pre_registration->account_no)->get();

            if ($verified_account_by_qr->count() > 0 || $verified_account_account_no->count() > 0) {
                return response()->json(['message' => 'Account already register' ,'status_message' => 'error']);
            }
            else{
                // INSERT ELECTRICIAN COMPLAINT
                DB::table('agmm_verified_accounts')->insert(
                    array(
                        'account_no' => $pre_registration->account_no,
                        'name' => $pre_registration->last_name.', '.$pre_registration->first_name.' '.$pre_registration->middle_name,
                        'contact_no' => $pre_registration->contact_no,
                        'membership_or' => $pre_registration->membership_or,
                        'registration_type' => $request->consumerType,
                        'qr_code_value' => $uuid,
                        'transpo_allowance' => $allowance ? $allowance : 0,
                        'claimed_by' => "", 
                        'allowance_status' => false,
                        'verified_by' => Auth::id(),
                        'remarks' => "",
                        'created_at' => now()
                    )
                );
                DB::commit();
            }
            

            return response()->json(['message' => 'Successfully Register', 'reg_details' => $pre_registration ,'status_message' => 'success']);
            
        } catch (\Throwable $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollBack();

            // Return an error response or redirect to an error page
            return response()->json(['message' => $e ,'status_message' => 'error']);

        }
        return redirect()->back()->withSuccess('Applications Successfully Uploaded!');
    }

    public function statusCount(){
        $total_pre_registered_accounts = DB::table('agmms')->select('*')->count();
        $total_pre_registered_accounts_online = DB::table('agmms')->where('registration_type', 'ONLINE-PRE-REGISTRATION')->select('*')->count();
        $total_pre_registered_accounts_offline = DB::table('agmms')->where('registration_type', '!=', 'ONLINE-PRE-REGISTRATION')->select('*')->count();
        $total_verified_accounts = DB::table('agmm_verified_accounts')->select('*')->count();
        $total_allowance_count = DB::table('agmm_verified_accounts')->select('*')->where('allowance_status', true)->count();
        $total_allowance_disbursed = DB::table('agmm_verified_accounts')
        ->where('allowance_status', true)
        ->sum(DB::raw('CAST(transpo_allowance AS INT)'));

        $registered_accounts_per_area = DB::table('agmm_verified_accounts as v')
        ->join('agmm_ref_allowance as allowance', DB::raw('LEFT(v.account_no, 2)'), '=', 'allowance.area_code')
        ->select(DB::raw('LEFT(v.account_no, 2) as area_code'), DB::raw('COUNT(*) as count'), 'allowance.area')
        ->groupBy(DB::raw('LEFT(v.account_no, 2)'),'allowance.area')
        ->orderBy('area_code', 'asc')
        ->get();
        // dd($registered_accounts_per_area);

        $total_verified_consumers_per_user = DB::table('agmm_verified_accounts as verified_account')
        ->join('users as user', 'verified_account.verified_by', '=', 'user.id')
        ->select('user.name', DB::raw('COUNT(*) as count'))
        ->groupBy('verified_account.verified_by', 'user.name')
        ->get();

        $total_disbursed_allowances_per_user = DB::table('agmm_verified_accounts as verified_account')
        ->join('users as user', 'verified_account.claimed_by', '=', 'user.id')
        ->select('user.name', DB::raw('COUNT(*) as count'), DB::raw('SUM(CAST(transpo_allowance AS INT)) as total_allowance'))
        ->groupBy('verified_account.claimed_by', 'user.name')
        ->get();

        // dd($total_disbursed_allowances_per_user);

        return view('agmm.agmm_status_count')->with(compact('total_verified_accounts','total_pre_registered_accounts','total_allowance_count', 'total_allowance_disbursed', 'registered_accounts_per_area', 'total_verified_consumers_per_user', 'total_disbursed_allowances_per_user', 'total_pre_registered_accounts_online', 'total_pre_registered_accounts_offline'));
    }

    public function agmmRaffle(Request $request){
        $ref_areas = DB::table('agmm_ref_allowance')->get();

        // Check if request has values for 'municipality' and 'winners_count'
        if ($request->has(['municipality', 'winners_count'])) {

            $validated = $request->validate([
                'municipality' => 'nullable',
                'winners_count' => 'required|integer',
            ]);
            
            $municipality = $validated['municipality'];
            $winnersCount = $validated['winners_count'];
            
            // Fetch the winners based on the first two digits of account_no
            $winners = DB::table('agmm_verified_accounts as va')
            ->leftJoin('Consumers Table as ct', 'ct.Accnt No', '=', 'va.account_no')
            ->where('raffle_draw', null);

            if ($municipality) {
                // Fetch the winners based on the first two digits of account_no
                $winners->where(DB::raw('LEFT(account_no, 2)'), $municipality);
            }

            $winners = $winners->inRandomOrder()
            ->take($winnersCount)
            ->get();

            $winnerIds = $winners->pluck('id');

            DB::transaction(function () use ($winnerIds) {
                DB::table('agmm_verified_accounts')
                    ->whereIn('id', $winnerIds)
                    ->update(['raffle_draw' => true]);
            });


        } else {
            $winners = collect();
        }

        // dd($winners);
        return view('agmm.agmm_raffle_draw')->with(compact('ref_areas', 'winners'));
        
    }
}
