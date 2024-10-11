<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\PaymentTransactionFees;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\Helper;

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

        return view('power_bill.teller.payment_transaction.create')->with(compact('municipalities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
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
