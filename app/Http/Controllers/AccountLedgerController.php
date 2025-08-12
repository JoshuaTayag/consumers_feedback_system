<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChangeMeterRequest;
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
            $account->where('Serial No', 'like', "%$serial_no%");
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

            $change_meters = ChangeMeterRequest::where('account_number', $account_number)->pluck('control_no', 'id');
            // dd($change_meters);

            return view('billing.ledger_index',compact('account', 'ledger_history', 'ledger_history_kwh', 'change_meters'));

        } else{
            return redirect()->route('ledger.index')->with('error', 'No Record Found!');
        }
    }

    public function fetchAccount(Request $request){

        $account_no = $request->account_no;
        if(isset($request) && is_numeric($account_no)){

            $cons = DB::connection('sqlSrvBilling')->table('Consumers Table')
                ->where('Accnt No', '=', $account_no)
                ->where('Acct Stat', '=', '1')
                ->first();
            // dd($cons);
            // Check if consumer exist
            if($cons){
                $consumer['details'] = DB::connection('sqlSrvBilling')->table('Consumers Table')
                    ->join('Ledger Table', 'Ledger Table.Account No', '=', 'Consumers Table.Accnt No')
                    ->where('Consumers Table.Accnt No', '=', $account_no)
                    ->where('Consumers Table.Acct Stat', '=', '1')
                    ->where('Ledger Table.PaidOR')
                    ->orderBy('Ledger Table.RateID', 'desc')
                    ->select('Ledger Table.RateID', 'Ledger Table.BillAmt', 'Ledger Table.BillNo','Ledger Table.KWH Used as kwh_used', 'Ledger Table.DueDate', 'Consumers Table.Name')
                    ->take(1)
                    ->get();
                if(!empty($consumer['details'][0])){
                    $bill_no = $consumer['details'][0]->RateID;
                    $due_date = $consumer['details'][0]->DueDate;

                    
                    $consumer['current_date'] = date('F', mktime(0, 0, 0, substr($bill_no, 4, -1), 10)). " - " .substr($bill_no, 0, 4);

                    $consumer['due_date'] = date('F', mktime(0, 0, 0, substr($due_date, 5, -16), 10)) . " " . substr($due_date, 8, -12). ", " . substr($due_date, 0, -19);

                    $consumer['total_bill'] = DB::connection('sqlSrvBilling')->table('Consumers Table as consumer_table')
                        ->join('Ledger Table as ledgerTable', 'ledgerTable.Account No', '=', 'consumer_table.Accnt No')
                        ->where('consumer_table.Accnt No', '=', $account_no)
                        ->where('consumer_table.Acct Stat', '=', '1')
                        ->where('ledgerTable.PaidOR')
                        ->select('ledgerTable.BillAmt')
                        ->get()
                        ->pluck('BillAmt')->sum();

                    $consumer['status_message'] = "true";
                    return $consumer;

                }
                else{
                    $consumer['details'] = [['BillAmt' => 0.0, 'kwh_used' => "None", 'Name' => $cons->Name]];
                    $consumer['total_bill'] = 0.0;
                    $consumer['current_date'] = "None";
                    $consumer['due_date'] = "None";

                    $consumer['status_message'] = "true";
                    return $consumer;
                }
            }
            else{
                $consumer['status_message'] = "false";
                return $consumer;
            }
        }
    }

    public function fetchAccountDetails($type, $value)
    {
        // Validate 'name' type: must be a string
        if ($type === 'name' && !is_string($value)) {
            return response()->json([
                'status_message' => 'Invalid value. Name must be a string.'
            ], 400);
        }

        // Validate 'account_no' type: must be exactly 10 digits
        if ($type === 'account_no') {
            if (!ctype_digit($value)) {
                return response()->json([
                    'status_message' => 'Invalid value. Account No must contain only digits.'
                ], 400);
            }

            if (strlen($value) !== 10) {
                return response()->json([
                    'status_message' => 'Invalid value. Account No must be exactly 10 characters long.'
                ], 400);
            }
        }

        // Fetch data from SQL Server
        $data['details'] = DB::connection('sqlSrvBilling')
            ->table('Consumers Table')
            ->when($type === 'name', function ($query) use ($value) {
                return $query->where('Consumers Table.Name', 'LIKE', "%{$value}%");
            }, function ($query) use ($value) {
                return $query->where('Consumers Table.Accnt No', '=', $value);
            })
            ->select('Name', 'Accnt No', 'Serial No')
            ->take(10)
            ->get()
            ->map(function ($item) {
                return array_map(fn($value) => is_string($value) ? trim(utf8_encode($value)) : $value, (array) $item);
            });

        return response()->json([
            'status_message' => 'success',
            'details' => $data['details']
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function getAccountName(Request $request){
        $search = $request->search;

        if($search == ''){

            $accounts = DB::table('Consumers Table as ct')
            ->select(
                'ct.Accnt No as id',
                'ct.Name',
            );

        }else{

            $accounts = DB::table('Consumers Table as ct')
            ->select(
                'ct.Accnt No as id',
                'ct.Name',
            )
            ->where('ct.Name', 'like', '%' .$search . '%');
        }
        $data = $accounts->paginate(10, ['*'], 'page', $request->page);
        // dd($data);
        return response()->json($data); 
    } 
    

}
