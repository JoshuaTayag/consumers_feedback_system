<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgmaRaffleWinner;
use App\Models\Agmm;
use DB;
class AgmaRaffleWinnerController extends Controller
{
     // GET /raffle — load the view with participants from agmms
    public function index()
    {
        // Get already-won account numbers from SQL Server
        $wonAccountNos = DB::connection('sqlsrv')
            ->table('agma_raffle_winners')
            ->pluck('account_no')
            ->toArray();

        // Get consumer IDs from MySQL (agmm_db) that haven't won yet and were verified on July 11 at or before 12:01 PM
        $consumerIds = DB::connection('agmm_db')
            ->table('verifications')
            ->where('is_removed', false)
            ->where('consumer_type', 'mco')
            ->where('is_attended', true)
            ->whereBetween('verified_at', ['2026-07-11 00:00:00', '2026-07-11 12:00:00'])
            ->whereNotIn('consumer_id', $wonAccountNos)
            ->pluck('consumer_id')
            ->toArray();

            // dd(DB::connection('sqlSrvBilling')->table('Ledger Table')->take(10)->get());

        // Fetch consumer details in chunks from SQL Server
        $participants = [];
        foreach (array_chunk($consumerIds, 200) as $chunk) {
            $accountList = implode(',', array_map(fn ($id) => "'" . str_replace("'", "''", $id) . "'", $chunk));

            $query = sprintf(
                "SELECT ct.[Accnt No] as account_no, ct.[Name] as name, ct.[ConMunicipality] as municipality
                 FROM [Consumers Table] as ct
                 LEFT JOIN [Ledger Table] as lt ON lt.[Account No] = ct.[Accnt No]
                 WHERE ct.[Accnt No] IN (%s)
                 GROUP BY ct.[Accnt No], ct.[Name], ct.[ConMunicipality]
                 HAVING COUNT(CASE WHEN COALESCE(lt.[BillAmt], 0) > 0 THEN 1 END) <= 1",
                $accountList
            );

            $chunkData = DB::connection('sqlSrvBilling')->select($query);

            foreach ($chunkData as $p) {
                $participants[] = [
                    'account_no' => $p->account_no,
                    'name'       => trim($p->name),
                    'municipality' => trim($p->municipality),
                ];
            }
        }
        $municipalities = collect($participants)->pluck('municipality')->unique()->sort()->values();

        return view('agmm.agmm_raffle', compact('participants', 'municipalities'));
    }

    // GET /raffle/winners
    public function winners()
    {
        return response()->json(
            AgmaRaffleWinner::latest()->get(['id', 'account_no', 'name', 'prize', 'created_at'])
        );
    }

    // POST /raffle/winners
    public function store(Request $request)
    {
        $request->validate([
            'winners'              => 'required|array|min:1',
            'winners.*.account_no' => 'required|string',
            'winners.*.name'       => 'required|string',
            'prize'                => 'required|string|max:255',
        ]);

        $records = collect($request->winners)->map(fn($w) =>
            AgmaRaffleWinner::create([
                'account_no' => $w['account_no'],
                'name'       => $w['name'],
                'prize'      => $request->prize,
            ])
        );

        return response()->json($records, 201);
    }

    // DELETE /raffle/winners
    public function destroyAll()
    {
        AgmaRaffleWinner::truncate();
        return response()->json(['message' => 'History cleared.']);
    }

    // GET /raffle/winners/export
    public function export()
    {
        $winners = AgmaRaffleWinner::select('agma_raffle_winners.account_no', 'ct.Name as name', 'ct.Address as address', 'ct.ConMunicipality as municipality', 'prize', 'created_at as drawn_date')
            ->join('Consumers Table as ct', 'ct.Accnt No', '=', 'agma_raffle_winners.account_no')
            ->latest()
            ->get()
            ->map(fn($w) => [
                'account_no' => $this->formatAccountNo($w->account_no),
                'name'       => trim($w->name),
                'address'    => trim($w->address),
                'municipality' => trim($w->municipality),
                'prize'      => $w->prize,
                'drawn_date' => $w->drawn_date,
            ]);

        $csv = "account_no,name,address,municipality,prize,date_time\n";
        foreach ($winners as $w) {
            $csv .= "\"{$w['account_no']}\",\"{$w['name']}\",\"{$w['address']}\",\"{$w['municipality']}\",\"{$w['prize']}\",\"{$w['drawn_date']}\"\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="raffle_winners.csv"',
        ]);
    }
     // GET /raffle — load the view with participants from agmms
    public function display()
    {
        $winners = AgmaRaffleWinner::select('id', 'agma_raffle_winners.account_no', 'ct.Name as name', 'ct.Address as address', 'ct.ConMunicipality as municipality', 'prize')
            ->join('Consumers Table as ct', 'ct.Accnt No', '=', 'agma_raffle_winners.account_no')
            ->latest()
            ->get()
            ->map(fn($w) => [
                'id' => $w->id,
                'account_no' => $this->formatAccountNo($w->account_no),
                'name'       => trim($w->name),
                'address'    => trim($w->address),
                'municipality' => trim($w->municipality),
                'prize'      => $w->prize,
            ]);
        return view('agmm.agmm_raffle_winners', compact('winners'));
    }

    private function formatAccountNo($accountNo)
    {
        $clean = preg_replace('/\D/', '', $accountNo);
        if (strlen($clean) !== 10) {
            return $accountNo;
        }

        return substr($clean, 0, 2) . '-' . substr($clean, 2, 4) . '-' . substr($clean, 6, 4);
    }

    public function destroy($id)
    {
        $winner = AgmaRaffleWinner::findOrFail($id);
        $winner->delete();
        return redirect()->back()->with('success', 'Winner removed successfully.');
    }

    public function exportConsumersCsv(){
         // Get already-won account numbers from SQL Server
        $wonAccountNos = DB::connection('sqlsrv')
            ->table('agma_raffle_winners')
            ->pluck('account_no')
            ->toArray();

        // Get consumer IDs from MySQL (agmm_db) that haven't won yet and were verified on July 11 at or before 12:01 PM
        $consumerIds = DB::connection('agmm_db')
            ->table('verifications')
            ->where('is_removed', false)
            ->where('consumer_type', 'mco')
            ->where('is_attended', true)
            ->whereBetween('verified_at', ['2026-07-11 00:00:00', '2026-07-11 12:00:00'])
            ->whereNotIn('consumer_id', $wonAccountNos)
            ->pluck('consumer_id')
            ->toArray();

            // dd(DB::connection('sqlSrvBilling')->table('Ledger Table')->take(10)->get());

        // Fetch consumer details in chunks from SQL Server
        $participants = [];
        foreach (array_chunk($consumerIds, 200) as $chunk) {
            $accountList = implode(',', array_map(fn ($id) => "'" . str_replace("'", "''", $id) . "'", $chunk));

            $query = sprintf(
                "SELECT ct.[Accnt No] as account_no, ct.[Name] as name, ct.[ConMunicipality] as municipality
                 FROM [Consumers Table] as ct
                 LEFT JOIN [Ledger Table] as lt ON lt.[Account No] = ct.[Accnt No]
                 WHERE ct.[Accnt No] IN (%s)
                 GROUP BY ct.[Accnt No], ct.[Name], ct.[ConMunicipality]
                 HAVING COUNT(CASE WHEN COALESCE(lt.[BillAmt], 0) > 0 THEN 1 END) <= 1",
                $accountList
            );

            $chunkData = DB::connection('sqlSrvBilling')->select($query);

            foreach ($chunkData as $p) {
                $participants[] = [
                    'account_no' => $p->account_no,
                    'name'       => trim($p->name),
                    'municipality' => trim($p->municipality),
                ];
            }
        }

        // Create a CSV file and download it
        $csv = fopen('php://temp', 'w');
        fputcsv($csv, ['Account No', 'Name', 'Municipality']);

        foreach ($participants as $participant) {
            fputcsv($csv, $participant);
        }

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="consumers.csv"'
        ]);
    }
}