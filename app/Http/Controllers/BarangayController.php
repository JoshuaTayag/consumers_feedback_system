<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataManagement\Barangay;
use App\Models\DataManagement\Municipality;
use Illuminate\Validation\Rule;

class BarangayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Barangay::with('municipality')->orderBy('barangay_name','asc');

        if ($request->has('barangay') && $request->get('barangay') !== '') {
            $query->where('barangay_name', 'like', '%' . $request->get('barangay') . '%');
        }

        $barangays = $query->paginate(15);
        return view('data_management.barangays.index', compact('barangays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $municipalities = Municipality::orderBy('municipality_name', 'ASC')->get();
        return view('data_management.barangays.create', compact('municipalities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barangay_name' => 'required|string|max:255',
            'municipality_id' => [
              'required',
              Rule::exists('sqlSrvMembership.municipalities', 'id'),
            ],
        ]);

        Barangay::create($request->all());

        return redirect()->route('barangays.index')->with('success', 'Barangay created successfully.');
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
        $barangay = Barangay::findOrFail($id);
        $municipalities = Municipality::orderBy('municipality_name', 'ASC')->get();
        return view('data_management.barangays.edit', compact('barangay', 'municipalities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'barangay_name' => 'required|string|max:255',
            'municipality_id' => [
                'required',
                Rule::exists('sqlSrvMembership.municipalities', 'id'),
            ],
        ]);

        $barangay = Barangay::findOrFail($id);
        $barangay->update($request->all());

        return redirect()->route('barangays.index')->with('success', 'Barangay updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barangay = Barangay::findOrFail($id);
        $barangay->delete();

        return redirect()->route('barangays.index')->with('success', 'Barangay deleted successfully.');
    }

    public function exportCsv()
    {
        $barangays = Barangay::with('municipality')->orderBy('barangay_name', 'asc')->get();
        $csv = fopen('php://temp', 'w');
        fputcsv($csv, ['Barangay Name', 'Municipality Name']);

        foreach ($barangays as $barangay) {
            fputcsv($csv, [
                $barangay->barangay_name,
                $barangay->municipality->municipality_name ?? ''
            ]);
        }

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        return response()->make($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="barangays.csv"'
        ]);
    }
}
