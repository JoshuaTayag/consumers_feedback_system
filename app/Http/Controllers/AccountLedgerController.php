<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChangeMeterRequest;
use DB;

class AccountLedgerController extends Controller
{
    public function indexLedger(Request $request)
    {
        $ledger_history_kwh = "";
        return view('billing.ledger_index', compact('ledger_history_kwh'));
    }

    public function searchLedger(Request $request)
    {
        $account_no   = $request->input('account_no');
        $account_name = $request->input('account_name');
        $serial_no    = $request->input('serial_no');

        if (empty($account_no) && empty($account_name) && empty($serial_no)) {
            return redirect()->route('ledger.index')->with('error', 'No Record Found!');
        }

        // Select only the columns the view actually needs instead of '*'.
        // Adjust this list to match what billing.ledger_index uses.
        $query = DB::connection('sqlSrvBilling')
            ->table('Consumers Table')
            ->select('Accnt No', 'Name', 'Serial No', 'Acct Stat', 'Address', 'Cons Type', 'Brand', 'Noma', 'Seq-No','Book No', 'Remarks', 'Latitude','Longitude'); // trim/extend as needed

        if (!empty($account_no)) {
            // Exact match instead of LIKE where possible — allows index usage
            // instead of a full scan caused by a leading wildcard.
            $query->where('Accnt No', $account_no);
        }

        if (!empty($account_name)) {
            $query->where('Name', 'like', "%{$account_name}%");
        }

        if (!empty($serial_no)) {
            $query->where('Serial No', 'like', "%{$serial_no}%");
        }

        // Single query instead of get()->isEmpty() followed by first().
        $account = $query->first();

        if (!$account) {
            return redirect()->route('ledger.index')->with('error', 'No Record Found!');
        }

        $account_number = $account->{'Accnt No'};

        $ledger_history = DB::connection('sqlSrvBilling')
            ->table('Ledger Table')
            ->select('BillNo', 'BillAmt', 'DueDate', 'PaidOR', 'RateID', 'KWH Used','BillDate','Account No','HisPrev','Present Reading','KWH Used','Billed') // trim/extend as needed
            ->where('Account No', $account_number) // exact match; drop LIKE/wildcards
            ->orderByDesc('DueDate')
            ->limit(500) // guard rail: avoid loading unbounded history into memory
            ->get();

        $ledger_history_kwh = DB::connection('sqlSrvHistory')
            ->table('History Table')
            ->selectRaw("
                CAST(SUBSTRING(CAST(YearMonth AS VARCHAR), 1, 4) + '-' + SUBSTRING(CAST(YearMonth AS VARCHAR), 5, 2) + '-01' AS DATE) as date,
                CAST([KWH Used] AS INT) as value
            ")
            ->where('Account No', $account_number)
            ->groupBy(
                DB::raw("CAST(SUBSTRING(CAST(YearMonth AS VARCHAR), 1, 4) + '-' + SUBSTRING(CAST(YearMonth AS VARCHAR), 5, 2) + '-01' AS DATE)"),
                DB::raw("CAST([KWH Used] AS INT)")
            )
            ->orderBy(DB::raw("CAST(SUBSTRING(CAST(YearMonth AS VARCHAR), 1, 4) + '-' + SUBSTRING(CAST(YearMonth AS VARCHAR), 5, 2) + '-01' AS DATE)"), 'asc')
            ->get();
        // dd($account);
        $change_meters = ChangeMeterRequest::where('account_number', $account_number)
            ->pluck('control_no', 'id');

        return view('billing.ledger_index', compact('account', 'ledger_history', 'ledger_history_kwh', 'change_meters'));
    }

    public function fetchAccount(Request $request)
    {
        $account_no = $request->account_no;

        if (!is_numeric($account_no)) {
            return ['status_message' => 'false'];
        }

        $cons = DB::connection('sqlSrvBilling')->table('Consumers Table')
            ->where('Accnt No', $account_no)
            ->where('Acct Stat', '1')
            ->first(['Accnt No', 'Name']);

        if (!$cons) {
            return ['status_message' => 'false'];
        }

        // Single query pulls everything needed for both "latest bill details"
        // and "total_bill" — the original code ran two separate joined
        // queries against Consumers Table + Ledger Table for this.
        $bills = DB::connection('sqlSrvBilling')->table('Ledger Table')
            ->where('Account No', $account_no)
            ->whereNotNull('PaidOR') // adjust if PaidOR isn't nullable — see note below
            ->orderByDesc('RateID')
            ->select('RateID', 'BillAmt', 'BillNo', DB::raw('[KWH Used] as kwh_used'), 'DueDate')
            ->get();

        if ($bills->isEmpty()) {
            return [
                'details'      => [['BillAmt' => 0.0, 'kwh_used' => 'None', 'Name' => $cons->Name]],
                'total_bill'   => 0.0,
                'current_date' => 'None',
                'due_date'     => 'None',
                'status_message' => 'true',
            ];
        }

        $latest   = $bills->first();
        $bill_no  = $latest->RateID;
        $due_date = $latest->DueDate;

        $detailsRow = (array) $latest;
        $detailsRow['Name'] = $cons->Name;

        return [
            'details'      => [$detailsRow],
            'total_bill'   => $bills->sum('BillAmt'), // summed in-memory, no extra DB round trip
            'current_date' => date('F', mktime(0, 0, 0, substr($bill_no, 4, -1), 10)) . " - " . substr($bill_no, 0, 4),
            'due_date'     => date('F', mktime(0, 0, 0, substr($due_date, 5, -16), 10)) . " " . substr($due_date, 8, -12) . ", " . substr($due_date, 0, -19),
            'status_message' => 'true',
        ];
    }

    public function fetchAccountDetails($type, $value)
    {
        if ($type === 'name' && !is_string($value)) {
            return response()->json([
                'status_message' => 'Invalid value. Name must be a string.'
            ], 400);
        }

        if ($type === 'account_no') {
            if (!ctype_digit($value) || strlen($value) !== 10) {
                return response()->json([
                    'status_message' => 'Invalid value. Account No must be exactly 10 digits.'
                ], 400);
            }
        }

        $details = DB::connection('sqlSrvBilling')
            ->table('Consumers Table')
            ->when(
                $type === 'name',
                fn ($query) => $query->where('Name', 'LIKE', "%{$value}%"),
                fn ($query) => $query->where('Accnt No', '=', $value)
            )
            ->select('Name', 'Accnt No', 'Serial No')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return array_map(
                    // utf8_encode() is deprecated as of PHP 8.2. mb_convert_encoding
                    // is the direct replacement for the common Latin-1 -> UTF-8 case.
                    // Longer term, fixing the column/connection collation on the SQL
                    // Server side removes the need for this conversion entirely.
                    fn ($v) => is_string($v) ? trim(mb_convert_encoding($v, 'UTF-8', 'ISO-8859-1')) : $v,
                    (array) $item
                );
            });

        return response()->json([
            'status_message' => 'success',
            'details' => $details,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function getAccountName(Request $request)
    {
        $search = trim((string) $request->search);

        $query = DB::table('Consumers Table as ct')
            ->select('ct.Accnt No as id', 'ct.Name');

        if ($search !== '') {
            $query->where('ct.Name', 'like', "%{$search}%");
        }

        $query->orderBy('ct.Name');

        return response()->json(
            $query->paginate(10, ['*'], 'page', $request->page)
        );
    }
}