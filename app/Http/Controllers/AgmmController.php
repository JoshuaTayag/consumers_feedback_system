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
}
