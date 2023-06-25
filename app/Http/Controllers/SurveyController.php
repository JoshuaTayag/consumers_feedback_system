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
        return redirect('/')->withSuccess('Survey successfully submitted!');
    }
}
