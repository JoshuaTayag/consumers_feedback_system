<?php

namespace App\Http\Controllers;

use App\Models\ChangeMeterRequestContractor;
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
        return view('service_connect_order.change_meter.contractor.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'mobile_number' => 'required|string|max:20',
            'user_id' => 'nullable|exists:users,id',
        ]);

        ChangeMeterRequestContractor::create($request->all());

        return redirect()->route('change-meter-contractor.index')
                         ->with('success','Contractor created successfully.');
    }

    public function edit($id)
    {
        $contractor = ChangeMeterRequestContractor::findOrFail($id);
        $users = User::pluck('email', 'id');
        return view('service_connect_order.change_meter.contractor.edit', compact('contractor', 'users'));
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