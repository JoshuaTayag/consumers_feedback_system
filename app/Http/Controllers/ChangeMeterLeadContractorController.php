<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChangeMeterLeadContractor;

class ChangeMeterLeadContractorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lead_contractors = ChangeMeterLeadContractor::orderBy('contractor_team_leader_full_name','asc')->paginate(10);
        return view('service_connect_order.change_meter.contractor_team_lead.index',compact('lead_contractors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('service_connect_order.change_meter.contractor_team_lead.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contractor_team_leader_full_name' => 'required|string|max:255|unique:change_meter_lead_contractors,contractor_team_leader_full_name',
            'area' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
        ]);

        ChangeMeterLeadContractor::create($request->all());

        return redirect()->route('change-meter-lead-contractor.index')
                         ->with('success','Lead Contractor created successfully.');
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
        $lead_contractor = ChangeMeterLeadContractor::findOrFail($id);
        return view('service_connect_order.change_meter.contractor_team_lead.edit', compact('lead_contractor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'contractor_team_leader_full_name' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
        ]);

        $lead_contractor = ChangeMeterLeadContractor::findOrFail($id);
        $lead_contractor->update($request->all());

        return redirect()->route('change-meter-lead-contractor.index')
                         ->with('success','Lead Contractor updated successfully.'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lead_contractor = ChangeMeterLeadContractor::findOrFail($id);
        $lead_contractor->delete();

        return redirect()->route('change-meter-lead-contractor.index')
                         ->with('success','Lead Contractor deleted successfully.');
    }
}
