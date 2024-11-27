<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\PaymentTransactionFees;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Config;

class PaymentTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        $last_or = PaymentTransaction::orderBy('id', 'desc')->where('processed_by', Auth::id())->first();

        $latest_or = $last_or ? $last_or->or_no : 0;

        // dd($latest_or);

        // Extract the letter part
        $letterPart = substr($latest_or, 0, 1);

        // Extract the numeric part and increment it
        $numericPart = (int) substr($latest_or, 1);
        $incrementedNumericPart = str_pad($numericPart + 1, 8, '0', STR_PAD_LEFT);

        // Combine the letter part with the incremented numeric part
        $new_or = $letterPart.$incrementedNumericPart;

        return view('power_bill.teller.payment_transaction.create')->with(compact('municipalities', 'new_or'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $control_id = Helper::IDGeneratorChangeMeter(new PaymentTransaction, 'control_no', 4, 'MT');
        // dd($control_id);
        DB::beginTransaction();

        
        
        try {

            $payment_transaction = PaymentTransaction::create([
                "or_no" => $request->or_no,
                "control_no" => $control_id,
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "municipality_id" => $request->municipality,
                "barangay_id" => $request->barangay,
                "sitio" => $request->sitio,
                "business_type_id" => 0,
                "consumer_type_id" => $request->consumer_type,
                "cheque_no" => $request->cheque_no,
                "cheque_date" => $request->cheque_date,
                "process_date" => $request->process_date,
                "created_at" => Carbon::today(),
                "processed_by" => Auth::id(),
            ]);
            // dd($payment_transaction);
            $feeFields = [
                'membership', 'energy_deposit', 'conn_fee', 'xformer_rental', 'xformer_test',
                'xformer_installation', 'xformer_removal', 'consumer_xfmr', 'consumer_pole',
                'grounding_clamp', 'grounding_rod', 'meter_seal', 'hotline_clamp',
                'meter_accessories', 'discredit_fee', 'calibration_fee', 'others',
                'housewiring_kit', 'excess_conductor', 'conductor_duplex', 'circuit_breaker'
            ];

            foreach ($feeFields as $feeField) {
                if ($request->$feeField > 0) {
                    // ChangeMeterRequestFees::create([
                    //     'cm_control_no' => $change_meter_request->id,
                    //     'fees' => $feeField,
                    //     'amount' => $request->$feeField
                    // ]);
                    // dd($payment_transaction->id);
                    PaymentTransactionFees::create([
                        "payment_transaction_id" => $payment_transaction->id,
                        "type_of_fees" => $feeField,
                        "amount_of_fees" => $request->$feeField,
                        "created_at" => Carbon::today(),
                    ]);
                }
            }
            
            // construct data
            $full_name = $payment_transaction->last_name.' '.$payment_transaction->first_name;
            $address = $payment_transaction->sitio.', '.($payment_transaction->barangay ? $payment_transaction->barangay->barangay_name : null).', '. ($payment_transaction->municipality ? $payment_transaction->municipality->municipality_name : null);
            
            // get consumer type word
            $consumerTypes = collect(Config::get('constants.consumer_types'));
            $consumerType = $consumerTypes->firstWhere('id', $payment_transaction->consumer_type_id);
            $consumerTypeName = $consumerType['name'] ?? 'Unknown Type';


            dd($consumerTypeName);

            $dbPath = "\\\\sql02\\files\\Con_Or.mdb";

            // Check if the file exists
            if (!file_exists($dbPath)) {
                throw new \Exception("Database file not found at: $dbPath");
            } else {
                // throw new \Exception("Database Found!: $dbPath");
                // Connection string for MS Access
                $connectionString = "Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=$dbPath;";
        
                // Attempt to connect
                $connection = odbc_connect($connectionString, "", "");
        
                if (!$connection) {
                    throw new \Exception("Failed to connect to the database.");
                }
        
                // Directly construct the SQL query with values
                $sql = "INSERT INTO [Tellering Table] (
                    [TLORNo],
                    [SCONo], 
                    [Name], 
                    [Received], 
                    [Address], 
                    [ProcessDate],
                    [ConsumerType], 
                    [Kwhm Deposit],
                    [Calibration],
                    [MeterSeal],
                    [Total],
                    [Style],
                    [AmtTender],
                    [Lastname],
                    [Firstname],
                    [UserName],

                    [evatKD],
                    [evatCalibration],
                    [evatMS],

                    [vatKD],    
                    [vatCAL],
                    [vatMS],

                    [vatTotal],
                    [Distribution],
                    [Vatable],
                    [OverallTotal],
                    [VATotal]

                ) VALUES (
                    '{$request->or_no}',
                    '{$cm_request->control_no}', 
                    '{$full_name}', 
                    '{$full_name}', 
                    '{$address}', 
                    '{$process_date}',
                    '{$consumerTypeName}', 
                    '{$meter_accessories_amount}',
                    '{$meter_calibration_amount}',
                    '{$meter_seal_amount}',
                    '{$total_fees_without_vat}',
                    '{$consumerTypeName}',
                    '{$amount_tendered}',
                    '{$cm_request->last_name}',
                    '{$cm_request->first_name}',
                    '{$userNameWithDate}',

                    '{$meter_accessories_vat_amount}',
                    '{$meter_calibration_vat_amount}',
                    '{$meter_seal_vat_amount}',


                    '{$total_meter_accessories_with_vat}',
                    '{$total_meter_calibration_with_vat}',
                    '{$total_meter_seal_with_vat}',

                    '{$vat_total}',
                    '{$distribution}',
                    '{$vatable}',
                    '{$vat_total}',
                    '{$distribution}'


                    

                )";
                
                // Execute the SQL statement
                $result = odbc_exec($connection, $sql);
        
                if (!$result) {
                    throw new Exception("SQL errors: " . odbc_errormsg($connection));
                }
        
                odbc_close($connection);

                DB::commit();

                $control_numbers = ChangeMeterRequest::doesntHave('changeMeterRequestTransaction')
                ->orderBy('id', 'desc')
                ->pluck('control_no','id');
        
                // return view('power_bill.teller.change_meter_request_payment.create', compact('control_numbers'));
                // return redirect(route('changeMeterReceipt'.$request->or_no));
                return redirect()->route('changeMeterReceipt', ['id' => $request->or_no]);
                // return redirect(route('change-meter-request-transact.create'))->withSuccess('Record Successfully Saved!');
            }

            DB::commit();

            return redirect(route('payment-transact.create'))->withSuccess('Successfully Transact! </br> Control No.:'.$control_id);

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
     * Display the specified resource.
     */
    public function show(PaymentTransaction $paymentTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentTransaction $paymentTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentTransaction $paymentTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentTransaction $paymentTransaction)
    {
        //
    }
}
