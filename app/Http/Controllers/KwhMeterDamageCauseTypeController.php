<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataManagement\KwhMeterDamageCauseType;

class KwhMeterDamageCauseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KwhMeterDamageCauseType::orderBy('name','asc');

        if ($request->has('name') && $request->get('name') !== '') {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        $kwhMeterDamageCauseTypes = $query->paginate(15);
        return view('data_management.kwh_meter_damage_cause.index', compact('kwhMeterDamageCauseTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('data_management.kwh_meter_damage_cause.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:kwh_meter_damage_cause_types,name',
            'description' => 'required|string|max:255',
        ]);

        KwhMeterDamageCauseType::create($request->all());

        return redirect()->route('kwh-meter-damage-cause-types.index')->with('success', 'Kwh Meter Damage Cause Type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kwhMeterDamageCauseType = KwhMeterDamageCauseType::findOrFail($id);
        return view('data_management.kwh_meter_damage_cause.edit', compact('kwhMeterDamageCauseType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:kwh_meter_damage_cause_types,name,' . $id,
            'description' => 'required|string|max:255',
        ]);

        $kwhMeterDamageCauseType = KwhMeterDamageCauseType::findOrFail($id);
        $kwhMeterDamageCauseType->update($request->all());

        return redirect()->route('kwh-meter-damage-cause-types.index')->with('success', 'Kwh Meter Damage Cause Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kwhMeterDamageCauseType = KwhMeterDamageCauseType::findOrFail($id);
        $kwhMeterDamageCauseType->delete();

        return redirect()->route('kwh-meter-damage-cause-types.index')->with('success', 'Kwh Meter Damage Cause Type deleted successfully.');
    }
}
