<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AccountLedgerController extends Controller
{
    public function indexLedger(Request $request){
        $ledger_history_kwh = "";
        return view('billing.ledger_index',compact('ledger_history_kwh'));
    }

    public function searchLedger(Request $request){

        $account_no = $request->input('account_no');
        $account_name = $request->input('account_name');
        $serial_no = $request->input('serial_no');

        if ($account_no == null && $account_name == null && $serial_no == null) {
            return redirect()->route('ledger.index')->with('error', 'No Record Found!');
        }

        $account = DB::connection('sqlSrvBilling')
        ->table('Consumers Table')
        ->select('*');

        if ($account_no !== null && $account_no !== '') {
            $account->where('Accnt No', 'like', "$account_no");
        }

        if ($account_name !== null && $account_name !== '') {
            $account->where('Name', 'like', "%$account_name%");
        }

        if ($serial_no !== null && $serial_no !== '') {
            $account->where('Serial No', 'like', "$serial_no");
        }
        
        
        if ($account->get()->isEmpty()) {
            return redirect()->route('ledger.index')->with('error', 'No Record Found!');
        } 

        if ($account_no !== null || $account_name !== null || $serial_no !== null){
            $account = $account->first();
            $account_number = $account ? $account->{'Accnt No'} : null;
            $ledger_history = DB::connection('sqlSrvBilling')
            ->table('Ledger Table')
            ->select('*')
            ->where('Account No', 'like', "%$account_number%")->get();

            $ledger_history_kwh = DB::connection('sqlSrvHistory')
            ->table('History Table')
            ->selectRaw("
                CAST(SUBSTRING(CAST(YearMonth AS VARCHAR), 1, 4) + '-' + SUBSTRING(CAST(YearMonth AS VARCHAR), 5, 2) + '-01' AS DATE) as date,
                CAST([KWH Used] AS INT) as value
            ")
            ->where('Account No', 'like', "%$account_number%")
            ->groupBy(DB::raw("CAST(SUBSTRING(CAST(YearMonth AS VARCHAR), 1, 4) + '-' + SUBSTRING(CAST(YearMonth AS VARCHAR), 5, 2) + '-01' AS DATE)"), DB::raw("CAST([KWH Used] AS INT)"))
            ->orderBy(DB::raw("CAST(SUBSTRING(CAST(YearMonth AS VARCHAR), 1, 4) + '-' + SUBSTRING(CAST(YearMonth AS VARCHAR), 5, 2) + '-01' AS DATE)"), 'asc')
            ->get();



            // dd($ledger_history_kwh);

            return view('billing.ledger_index',compact('account', 'ledger_history', 'ledger_history_kwh'));

        } else{
            return redirect()->route('ledger.index')->with('error', 'No Record Found!');
        }
    }

}
