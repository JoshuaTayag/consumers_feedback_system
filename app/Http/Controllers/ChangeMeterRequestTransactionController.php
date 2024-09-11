<?php

namespace App\Http\Controllers;

use App\Models\ChangeMeterRequestTransaction;
use Illuminate\Http\Request;
use App\Models\ChangeMeterRequest;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;


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
        ->pluck('control_no','id');
        // dd($control_numbers);
        return view('power_bill.teller.change_meter_request_payment.create', compact('control_numbers'));
    }

    public function createCMSearch(Request $request)
    {
        $cm_request = ChangeMeterRequest::findOrFail($request->control_no);
        $control_numbers = ChangeMeterRequest::doesntHave('changeMeterRequestTransaction')->orderBy('id','desc')->pluck('control_no','id');
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
            $meter_accessories_amount = $cm_request->cmr_fees()->where('fees', 'meter_accessories')->pluck('amount')->first();
            $meter_calibration_amount = $cm_request->cmr_fees()->where('fees', 'calibration_fee')->pluck('amount')->first();
            $meter_seal_amount = $cm_request->cmr_fees()->where('fees', 'meter_seal')->pluck('amount')->first();
            $total_fees = str_replace(',', '', $request->total_fees);
            $process_date = date('m/d/Y', strtotime($cm_request->created_at));
            $amount_tendered = str_replace(',', '', $request->amount_tendered);
            $userNameWithDate = Auth::user()->name.' - '.Carbon::now()->format('m/d/y h:i:s A');

            $meter_accessories_vat_amount = $meter_accessories_amount * .12;
            $total_meter_accessories_with_vat = $meter_accessories_amount + $meter_accessories_vat_amount;

            $meter_calibration_vat_amount = $meter_calibration_amount * .12;
            $total_meter_calibration_with_vat = $meter_calibration_amount + $meter_calibration_vat_amount;

            $meter_seal_vat_amount = $meter_seal_amount * .12;
            $total_meter_seal_with_vat = $meter_seal_amount + $meter_seal_vat_amount;



            $distribution = $meter_accessories_vat_amount+$meter_calibration_vat_amount+$meter_seal_vat_amount;

            $vat_total = $total_meter_accessories_with_vat+$total_meter_calibration_with_vat+$total_meter_seal_with_vat;
            $vatable = $vat_total / 1.12;

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
                    [VATotal],
                    [OR]
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
                    '{$total_fees}',
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
                    '{$distribution}',
                    '{$request->or_no}'
                )";
                
                // Execute the SQL statement
                $result = odbc_exec($connection, $sql);
        
                if (!$result) {
                    throw new \Exception("Failed to insert data.");
                }
        
                odbc_close($connection);

                DB::commit();

                $control_numbers = ChangeMeterRequest::doesntHave('changeMeterRequestTransaction')
                ->orderBy('id', 'desc')
                ->pluck('control_no','id');
        
                // return view('power_bill.teller.change_meter_request_payment.create', compact('control_numbers'));
                return redirect(route('change-meter-request-transact.create'))->withSuccess('Record Successfully Saved!');
            }
        } catch (\Exception $e) {
            // If an exception occurs during the transaction, rollback all changes
            DB::rollback();
                
            // Optionally, handle the exception (log it, display an error message, etc.)
            // For example:
            // Log::error($e->getMessage());
            return $e->getMessage();
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
}
