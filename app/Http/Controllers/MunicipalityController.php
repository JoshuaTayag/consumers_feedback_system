<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataManagement\Municipality;
use App\Models\DataManagement\District;
use Illuminate\Validation\Rule;

class MunicipalityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $municipalities = Municipality::orderBy('municipality_name','ASC')->paginate(15);
        return view('data_management.municipalities.index', compact('municipalities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = District::orderBy('district_name', 'ASC')->get();
        return view('data_management.municipalities.create', compact('districts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'municipality_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sqlSrvMembership.municipalities', 'municipality_name'),
            ],
            'district_id' => [
                'required',
                Rule::exists('sqlSrvMembership.districts', 'id'),
            ],
        ]);

        Municipality::create($request->all());

        return redirect()->route('municipalities.index')->with('success', 'Municipality created successfully.');
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
        $municipality = Municipality::findOrFail($id);
        $districts = District::orderBy('district_name', 'ASC')->get();
        return view('data_management.municipalities.edit', compact('municipality', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'municipality_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sqlSrvMembership.municipalities', 'municipality_name')->ignore($id),
            ],
            'district_id' => [
                'required',
                Rule::exists('sqlSrvMembership.districts', 'id'),
            ],
        ]);

        $municipality = Municipality::findOrFail($id);
        $municipality->update($request->all());

        return redirect()->route('municipalities.index')->with('success', 'Municipality updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $municipality = Municipality::findOrFail($id);
        $municipality->delete();

        return redirect()->route('municipalities.index')->with('success', 'Municipality deleted successfully.');
    }
}
