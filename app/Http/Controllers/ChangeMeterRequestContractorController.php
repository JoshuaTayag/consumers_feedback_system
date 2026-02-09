<?php

namespace App\Http\Controllers;

use App\Models\ChangeMeterRequestContractor;
use App\Models\ChangeMeterLeadContractor;
use App\Models\User;
use Illuminate\Http\Request;

class ChangeMeterRequestContractorController extends Controller
{
    public function index()
    {
        $contractors = ChangeMeterRequestContractor::orderBy('last_name','asc')->paginate(10);
        return view('service_connect_order.change_meter.contractor.index',compact('contractors'));
    }

    public function create()
    {
        $users = User::pluck('email', 'id');
        $lead_contractors = ChangeMeterLeadContractor::orderBy('contractor_team_leader_full_name','asc')->get();
        return view('service_connect_order.change_meter.contractor.create', compact('users', 'lead_contractors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'mobile_number' => 'required|string|max:20',
            'user_id' => 'nullable|exists:users,id',
            'team_leader_id' => 'nullable|exists:change_meter_lead_contractors,id',
            'status' => 'required',
        ]);

        ChangeMeterRequestContractor::create($request->all());

        return redirect()->route('change-meter-contractor.index')
                         ->with('success','Contractor created successfully.');
    }

    public function edit($id)
    {
        $contractor = ChangeMeterRequestContractor::findOrFail($id);
        $users = User::pluck('email', 'id');
        $lead_contractors = ChangeMeterLeadContractor::orderBy('contractor_team_leader_full_name','asc')->get();
        return view('service_connect_order.change_meter.contractor.edit', compact('contractor', 'users', 'lead_contractors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'mobile_number' => 'required|string|max:20',
            'user_id' => 'nullable|exists:users,id',
            'team_leader_id' => 'nullable|exists:change_meter_lead_contractors,id',
            'status' => 'required',
        ]);

        $contractor = ChangeMeterRequestContractor::findOrFail($id);
        $contractor->update($request->all());

        return redirect()->route('change-meter-contractor.index')
                         ->with('success','Contractor updated successfully.');
    }

    public function destroy($id)
    {
        $contractor = ChangeMeterRequestContractor::findOrFail($id);
        $contractor->delete();

        return redirect()->route('change-meter-contractor.index')
                         ->with('success','Contractor deleted successfully.');
    }
}