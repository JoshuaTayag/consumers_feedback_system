<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agmm;
use DB;
use Ramsey\Uuid\Uuid;

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
                $accountExists = Agmm::where('account_no', $request->account_no)->exists();
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

                    // Store the validated data in the database
                    $registration = Agmm::create($validatedData);

                    // Exclude timestamps from the registration collection
                    $registrationWithoutTimestamps = $registration->makeHidden(['created_at', 'updated_at']);

                    // Return a response
                    return response()->json(['message' => 'User successfully Registered', 'registration' => $registrationWithoutTimestamps, 'status_message' => 'success'], 201);
                }

            } catch (\Exception $e) {
                // Handle any unexpected exceptions
                return response()->json(['message' => 'Failed to process the request', 'status_message' => 'error'], 500);
            }
    
        } else {
            return response()->json($account);
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
        $registration = Agmm::where('account_no', $id)->get();

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
        $registration = Agmm::where('qr_code_value', $qr)->get();

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
        $registration = Agmm::where('qr_code_value', $qr);

        // Check if the account exists
        if ($registration->count() > 0) {

            // Check if the status is already claimed
            if ($registration->first()->allowance_status == true) {
                return response()->json(['message' => 'Allowance already claimed!', 'status_message' => 'success'], 201);
            } else{
                $registration->update(['allowance_status' => true]);
                return response()->json(['message' => 'Allowance successfully claimed!', 'status_message' => 'success'], 201);
            }
            
        } else {
            return response()->json(['message' => 'Registration does not exist', 'status_message' => 'error']);
        }
    }

    
}
