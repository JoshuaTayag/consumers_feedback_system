<?php

namespace App\Http\Controllers;

use App\Models\ChangeMeterRequestTransaction;
use Illuminate\Http\Request;
use App\Models\ChangeMeterRequest;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class ChangeMeterRequestTransactionController extends Controller
{

    function __construct()
    {
        //  $this->middleware('permission:cashier-transaction-list|lifeline-create|lifeline-edit|lifeline-delete', ['only' => ['index']]);
         $this->middleware('permission:cashier-transaction-create', ['only' => ['create', 'createCMSearch', 'store']]);
        //  $this->middleware('permission:lifeline-edit', ['only' => ['edit','update', 'approveLifeline', 'uploadLifeline']]);
        //  $this->middleware('permission:lifeline-delete', ['only' => ['destroy']]);
    }

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
        $control_numbers = ChangeMeterRequest::doesntHave('changeMeterRequestTransaction')
        ->orderBy('id', 'desc')
        ->where('status', null)
        ->pluck('control_no','id');
        // dd($control_numbers);
        return view('power_bill.teller.change_meter_request_payment.create', compact('control_numbers'));
    }

    public function createCMSearch(Request $request)
    {
        $cm_request = ChangeMeterRequest::findOrFail($request->control_no);
        $control_numbers = ChangeMeterRequest::doesntHave('changeMeterRequestTransaction')
        ->orderBy('id','desc')
        ->where('status', null)
        ->pluck('control_no','id');
        // dd($cm_requests);
        return view('power_bill.teller.change_meter_request_payment.create', compact('cm_request', 'control_numbers'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $change_meter_request_transaction = ChangeMeterRequestTransaction::create([
                "or_no" => $request->or_no,
                "change_meter_id" => $request->control_no,
                "total_fees" => str_replace(',', '', $request->total_fees),
                "total_amount_tendered" => str_replace(',', '', $request->amount_tendered),
                "change" => str_replace(',', '', $request->change),
                "created_at" => Carbon::today(),
            ]);

            $cm_request = ChangeMeterRequest::findOrFail($request->control_no);
            $full_name = $cm_request->last_name.' '.$cm_request->first_name;
            $address = $cm_request->sitio.', '.$cm_request->barangay->barangay_name.', '. $cm_request->municipality->municipality_name;

            // get consumer type word
            $consumerTypes = collect(Config::get('constants.consumer_types'));
            $consumerType = $consumerTypes->firstWhere('id', $cm_request->consumer_type);
            $consumerTypeName = $consumerType['name'] ?? 'Unknown Type'; // or $consumerType->name if it's an object

            // get meter accessories amount
            $meter_accessories_amount = $cm_request->cmr_fees()->where('fees', 'meter_accessories')->pluck('amount')->first() ? $cm_request->cmr_fees()->where('fees', 'meter_accessories')->pluck('amount')->first() : 0 ;
            $meter_calibration_amount = $cm_request->cmr_fees()->where('fees', 'calibration_fee')->pluck('amount')->first() ? $cm_request->cmr_fees()->where('fees', 'calibration_fee')->pluck('amount')->first() : 0;
            $meter_seal_amount = $cm_request->cmr_fees()->where('fees', 'meter_seal')->pluck('amount')->first() ? $cm_request->cmr_fees()->where('fees', 'meter_seal')->pluck('amount')->first() : 0;
            $total_fees_without_vat = str_replace(',', '', $request->total_fees_without_vat);
            $process_date = date('m/d/Y', strtotime($cm_request->created_at));
            $amount_tendered = str_replace(',', '', $request->amount_tendered);
            $userNameWithDate = Auth::user()->name.' - '.Carbon::now()->format('m/d/y h:i:s A');

            $meter_accessories_vat_amount = round($meter_accessories_amount * 0.12, 2);
            $total_meter_accessories_with_vat = $meter_accessories_amount + $meter_accessories_vat_amount;

            $meter_calibration_vat_amount = round($meter_calibration_amount * 0.12, 2);
            $total_meter_calibration_with_vat = $meter_calibration_amount + $meter_calibration_vat_amount;

            $meter_seal_vat_amount = round($meter_seal_amount * 0.12, 2);
            $total_meter_seal_with_vat = $meter_seal_amount + $meter_seal_vat_amount;



            $distribution = $meter_accessories_vat_amount+$meter_calibration_vat_amount+$meter_seal_vat_amount;

            $vat_total = $total_meter_accessories_with_vat+$total_meter_calibration_with_vat+$total_meter_seal_with_vat;
            $vatable = round($vat_total / 1.12, 2);

            // dd($userNameWithDate);
            $dbPath = "\\\\sql02\\files\\Con_Or.mdb";
            // dd($vatable);
        
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
        } catch (\Exception $e) {
            // If an exception occurs during the transaction, rollback all changes
            DB::rollback();
                
            // Optionally, handle the exception (log it, display an error message, etc.)
            // For example:
            // Log::error($e->getMessage());
            return dd($e);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(ChangeMeterRequestTransaction $changeMeterRequestTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChangeMeterRequestTransaction $changeMeterRequestTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChangeMeterRequestTransaction $changeMeterRequestTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChangeMeterRequestTransaction $changeMeterRequestTransaction)
    {
        //
    }

    public function changeMeterReceipt($or_no){ 
        // dd($or_no);

        $dbPath = "\\\\sql02\\files\\Con_Or.mdb";

        // Connection string for MS Access
        $connectionString = "Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=$dbPath;";

        // Attempt to connect
        $connection = odbc_connect($connectionString, "", "");

        if (!$connection) {
            throw new \Exception("Failed to connect to the database.");
        } else {
            $sqlRetrieve = "SELECT * FROM [Tellering Table] WHERE [TLORNo] = '{$or_no}'";

            // Execute the retrieval query
            $resultRetrieve = odbc_exec($connection, $sqlRetrieve);

            if ($row = odbc_fetch_array($resultRetrieve)) {
                // Now you have the inserted data in $row
                // You can print it or use it as needed
                // dd($row);
                $result = $row;
                // dd($result);
                // $total_in_words = $this->convertNumberToWords('2223.15');
                // dd($total_in_words.' - 2223.15');
                $api_request_result = $this->convertNumber($result['OverallTotal']);
                $convertedNumber = json_decode($api_request_result->body(), true);

                return view('power_bill.teller.change_meter_request_payment.receipt')->with(compact('result','convertedNumber'));

            } else {
                throw new \Exception("Failed to retrieve the inserted data.");
            }

        }

        
    }

    private function convertNumber($number)
    {
        // Create the URL dynamically based on the number
        $url = "https://api.numwords.us/en/{$number}";

        // Send a GET request to the API
        $response = Http::get($url);

        // Check if the request was successful (200 status code)
        if ($response->successful()) {
            // Return the response as it is
            return $response; // Return the raw response object
        } else {
            // Handle the error if the request failed
            return response()->json(['error' => 'Unable to convert number'], 500);
        }
    }
}
