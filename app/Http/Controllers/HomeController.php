<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->hasRole('Consumer')){
            return view('consumer.dashboard');
        }
        else{
            return view('home');
        }
        
    }

    public function surveyReport(Request $request)
    {
        $from = null;
        $to = null;
        $surveys = [];

        // dd($surveys);
        return view('survey_report')->with(compact('surveys', 'from', 'to'));
    }

    public function fetchSurvey(Request $request)
    {

        list($from, $to) = explode(' - ', $request->value);

        // dd($startDate);
        $data['survey_result'] = DB::table('surveys')
        ->select(DB::raw('count(vote) as total_vote, vote'))
        ->whereBetween('created_at', [$from, $to])
        ->groupBy('vote')
        ->get();
  
        return response()->json($data);
    }
}
