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
    //     // Use chunk() to avoid loading all winners into memory
    // $wonAccountNos = collect();
    // DB::connection('sqlsrv')
    //     ->table('agma_raffle_winners')
    //     ->orderBy('id')
    //     ->select('account_no')
    //     ->chunk(1000, function($winners) use (&$wonAccountNos) {
    //         $wonAccountNos = $wonAccountNos->merge($winners->pluck('account_no'));
    //     });

    // // Get eligible consumer IDs from MySQL
    // $consumerIds = DB::connection('agmm_db')
    //     ->table('verifications')
    //     ->where('is_removed', false)
    //     ->where('consumer_type', 'mco')
    //     ->where('is_attended', true)
    //     ->whereNotIn('consumer_id', $wonAccountNos->toArray())
    //     ->pluck('consumer_id')
    //     ->toArray();

    // // Fetch consumer details in larger chunks (200 instead of 100)
    // $participants = [];
    // foreach (array_chunk($consumerIds, 200) as $chunk) {
    //     $chunkData = DB::connection('sqlsrv')
    //         ->table('Consumers Table')
    //         ->whereIn('Accnt No', $chunk)
    //         ->select(
    //             'Accnt No as account_no',
    //             'TRIM(name) as name',  // ← Trim in database
    //             'TRIM(ConMunicipality) as municipality'
    //         )
    //         ->get();
        
    //     foreach ($chunkData as $p) {
    //         $participants[] = [
    //             'account_no' => $p->account_no,
    //             'name' => $p->name,  // ← Already trimmed
    //             'municipality' => $p->municipality,
    //         ];
    //     }
    // }

    // $municipalities = collect($participants)->pluck('municipality')->unique()->sort()->values();
    // return view('agmm.agmm_raffle', compact('participants', 'municipalities'));




    
        // Get already-won account numbers from SQL Server
        $wonAccountNos = DB::connection('sqlsrv')
            ->table('agma_raffle_winners')
            ->pluck('account_no')
            ->toArray();

        // Get consumer IDs from MySQL (agmm_db) that haven't won yet
        $consumerIds = DB::connection('agmm_db')
            ->table('verifications')
            ->where('is_removed', false)
            ->where('consumer_type', 'mco')
            ->where('is_attended', true)
            ->whereNotIn('consumer_id', $wonAccountNos)
            ->pluck('consumer_id')
            ->toArray();

        // Fetch consumer details in chunks from SQL Server
        $participants = [];
        foreach (array_chunk($consumerIds, 200) as $chunk) {
            $chunkData = DB::connection('sqlsrv')
                ->table('Consumers Table')
                ->whereIn('Accnt No', $chunk)
                ->select('Accnt No as account_no', 'Name as name', 'ConMunicipality as municipality')
                ->get();
            
            foreach ($chunkData as $p) {
                $participants[] = [
                    'account_no' => $p->account_no,
                    'name'       => trim($p->name),
                    'municipality' => trim($p->municipality),
                ];
            }
        }
        // $participants = Agmm::select('agmms.account_no', 'al.Name as name', 'al.ConMunicipality as municipality')
        //     ->join('Consumers Table as al', 'al.Accnt No', '=', 'agmms.account_no')
        //     ->whereNotIn('agmms.account_no', function ($query) {
        //         $query->select('account_no')->from('agma_raffle_winners');
        //     })
        //     ->get()
        //     ->map(fn($p) => [
        //         'account_no' => $p->account_no,
        //         'name'       => trim($p->name),
        //         'municipality' => trim($p->municipality),
        //     ]);
        // dd($participants);
        // $municipalities = $participants->pluck('municipality')->unique()->sort()->values();

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
}