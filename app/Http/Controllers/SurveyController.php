<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;

class SurveyController extends Controller
{
    public function store(Request $request)
    {
        // dd($request);
        $survey = new Survey;
        $survey->vote = $request->vote;
        $survey->save();
        return redirect('/survey')->withSuccess('Thank you for your feedback!');
    }
    public function storeCustcareSurvey(Request $request)
    {
        // dd($request);
        $survey = new Survey;
        $survey->vote = $request->vote;
        $survey->feedback_type = 1;
        $survey->save();
        return redirect('/survey-custcare')->withSuccess('Thank you for your feedback!');
    }
}
