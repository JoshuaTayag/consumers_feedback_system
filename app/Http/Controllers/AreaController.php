<?php

namespace App\Http\Controllers;

use App\Models\DataManagement\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Area::with('feeders')->orderBy('name','asc');

        if ($request->has('name') && $request->get('name') !== '') {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        $areas = $query->paginate(15);
        return view('data_management.areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('data_management.areas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:areas,name',
        ]);

        Area::create([
            'name' => $request->name,
        ]);

        return redirect()->route('areas.index')->with('success', 'Area created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        return view('data_management.areas.edit', compact('area'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'name' => 'required|unique:areas,name,' . $area->id,
        ]);

        $area->update([
            'name' => $request->name,
        ]);

        return redirect()->route('areas.index')->with('success', 'Area updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route('areas.index')->with('success', 'Area deleted successfully.');
    }
}
