<?php

namespace App\Http\Controllers;

use App\Models\DataManagement\Feeder;
use App\Models\DataManagement\Area;
use Illuminate\Http\Request;

class FeederController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Feeder::with('area')->orderBy('area_id','asc');

        if ($request->has('name') && $request->get('name') !== '') {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        $feeders = $query->paginate(15);
        return view('data_management.feeders.index', compact('feeders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $areas = Area::orderBy('name', 'asc')->get();
        return view('data_management.feeders.create', compact('areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $feeder = Feeder::create($request->all());
        return redirect()->route('feeders.index')->with('success', 'Feeder created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Feeder $feeder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feeder $feeder)
    {
        $areas = Area::orderBy('name', 'asc')->get();
        return view('data_management.feeders.edit', compact('feeder', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feeder $feeder)
    {
        $feeder->update($request->all());
        return redirect()->route('feeders.index')->with('success', 'Feeder updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feeder $feeder)
    {
        $feeder->delete();
        return redirect()->route('feeders.index')->with('success', 'Feeder deleted successfully.');
    }
}
