<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AccountLedgerController extends Controller
{
    public function indexLedger(Request $request){
        return view('billing.ledger_index');
    }

    public function searchLedger(Request $request){

        $account_no = $request->input('account_no');
        $account_name = $request->input('account_name');
        $serial_no = $request->input('serial_no');

        $account = DB::connection('sqlSrvBilling')
        ->table('Consumers Table')
        ->select('*');

        if ($account_no !== null && $account_no !== '') {
            $account->where('Accnt No', 'like', "%$account_no%");
        }

        if ($account_name !== null && $account_name !== '') {
            $account->where('Name', 'like', "%$account_name%");
        }

        if ($serial_no !== null && $serial_no !== '') {
            $account->where('Serial No', 'like', "%$serial_no%");
        }

        if ($account_no !== null || $account_name !== null || $serial_no !== null){
            $account = $account->first();
            $account_number = $account ? $account->{'Accnt No'} : null;
            $ledger_history = DB::connection('sqlSrvBilling')
            ->table('Ledger Table')
            ->select('*')
            ->where('Account No', 'like', "%$account_number%")->get();
        } else{
            $account = null;
            $ledger_history = [];
        }
        
        
        

        
        // dd($ledger_history);

        // return view('products.index', compact('products'));
        return view('billing.ledger_index',compact('account', 'ledger_history'));
    }

}
