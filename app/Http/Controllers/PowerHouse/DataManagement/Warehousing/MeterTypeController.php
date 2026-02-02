<?php

namespace App\Http\Controllers\PowerHouse\DataManagement\Warehousing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataManagement\MeterType;

class MeterTypeController extends Controller
{
    public function __construct() {
        $this->middleware('permission:kwh-meter-type-list|kwh-meter-type-create|kwh-meter-type-edit|kwh-meter-type-delete', ['only' => ['index']]);
        $this->middleware('permission:kwh-meter-type-create', ['only' => ['create', 'store', 'liquidate']]);
        $this->middleware('permission:kwh-meter-type-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:kwh-meter-type-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meter_types = MeterType::orderBy('id', 'desc')->paginate(10);
        return view('power_house.warehousing.data_management.meter_type.index', compact('meter_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('power_house.warehousing.data_management.meter_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'meter_brand' => ['required', 'string', 'max:255'],
            'meter_code' => ['required', 'string', 'max:255', 'unique:meter_types'],
            'meter_description' => ['required', 'string', 'max:255'],
            'meter_type' => ['required', 'string', 'max:255']
        ]);

        MeterType::create([
            'meter_brand' => $request->input('meter_brand'),
            'meter_code' => $request->input('meter_code'),
            'meter_description' => $request->input('meter_description'),
            'meter_type' => $request->input('meter_type')
        ]);

        return redirect()->route('meter-type.index')
                         ->with('success', 'Meter Type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $meter_type = MeterType::findOrFail($id);
        return view('power_house.warehousing.data_management.meter_type.edit', compact('meter_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'meter_brand' => ['required', 'string', 'max:255'],
            'meter_code' => ['required', 'string', 'max:255', 'unique:meter_types,meter_code,'.$id],
            'meter_description' => ['required', 'string', 'max:255'],
            'meter_type' => ['required', 'string', 'max:255']
        ]);

        $meter_type = MeterType::findOrFail($id);
        $meter_type->update([
            'meter_brand' => $request->input('meter_brand'),
            'meter_code' => $request->input('meter_code'),
            'meter_description' => $request->input('meter_description'),
            'meter_type' => $request->input('meter_type')
        ]);

        return redirect()->route('meter-type.index')
                         ->with('success', 'Meter Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $meter_type = MeterType::findOrFail($id);
        $meter_type->delete();

        return redirect()->route('meter-type.index')
                         ->with('success', 'Meter Type deleted successfully.');
    }
}
