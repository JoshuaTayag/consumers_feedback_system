<?php

namespace App\Http\Controllers\PowerHouse\Warehousing;

use App\Http\Controllers\Controller;
use App\Models\KwhMeterRequest;
use App\Models\User;
use App\Models\DataManagement\MeterType;
use Illuminate\Http\Request;

class KwhMeterRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kwh_meter_requests = KwhMeterRequest::orderBy('id', 'DESC')->paginate(15);
        return view('power_house.warehousing.kwh_meter_request.index', compact('kwh_meter_requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::role(['TSD', 'TSD Manager'])->pluck('name', 'id');
        $meters_types = MeterType::get();
        // dd($users);
        return view('power_house.warehousing.kwh_meter_request.create', compact('users', 'meters_types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // count unliquidated requests before submitting new one
        $unliquidatedCount = KwhMeterRequest::where('user_id', $request->user_id)
            ->where('is_liquidated', false)
            ->count();

        if ($unliquidatedCount >= 2) {
            return redirect()->back()->withErrors(['user_id' => 'You have unliquidated requests. Please liquidate them before submitting a new one.'])->withInput();
        }

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'purpose' => 'required|string|max:500',
            'meter_code_id' => 'required|exists:meter_types,id',
            'quantity' => 'required|integer|min:1|max:10',
            'approved_by' => 'required|exists:users,id',
        ]);

        KwhMeterRequest::create([
            'user_id' => $validatedData['user_id'],
            'purpose' => $validatedData['purpose'],
            'meter_code_id' => $validatedData['meter_code_id'],
            'quantity' => $validatedData['quantity'],
            'approved_by' => $validatedData['approved_by'],
            'is_liquidated' => false,
        ]);

        return redirect()->route('kwh-meter-request.index')->with('success', 'KWH Meter Request created successfully.');
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
        $kwh_meter_request = KwhMeterRequest::findOrFail($id);
        $users = User::role(['TSD', 'TSD Manager'])->pluck('name', 'id');
        $meters_types = MeterType::get();
        return view('power_house.warehousing.kwh_meter_request.edit', compact('kwh_meter_request', 'users', 'meters_types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // count unliquidated requests before submitting new one
        $unliquidatedCount = KwhMeterRequest::where('user_id', $request->user_id)
            ->where('is_liquidated', false)
            ->count();

        if ($unliquidatedCount >= 2) {
            return redirect()->back()->withErrors(['user_id' => 'This user has unliquidated requests. Please liquidate them before submitting a new one.'])->withInput();
        }

        $kwh_meter_request = KwhMeterRequest::findOrFail($id);

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'purpose' => 'required|string|max:500',
            'meter_code_id' => 'required|exists:meter_types,id',
            'quantity' => 'required|integer|min:1|max:10',
            'approved_by' => 'required|exists:users,id',
        ]);

        $kwh_meter_request->update([
            'user_id' => $validatedData['user_id'],
            'purpose' => $validatedData['purpose'],
            'meter_code_id' => $validatedData['meter_code_id'],
            'quantity' => $validatedData['quantity'],
            'approved_by' => $validatedData['approved_by'],
        ]);

        return redirect()->route('kwh-meter-request.index')->with('success', 'KWH Meter Request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // check status first before deleting
        $kwhMeterRequest = KwhMeterRequest::findOrFail($id);

        if ($kwhMeterRequest->is_liquidated) {
            return redirect()->route('kwh-meter-request.index')->withErrors(['error' => 'Cannot delete a liquidated request.']);
        }

        if ($kwhMeterRequest->approved_at !== null) {
            return redirect()->route('kwh-meter-request.index')->withErrors(['error' => 'Cannot delete an approved request.']);
        }

        $kwhMeterRequest->delete();
        return redirect()->route('kwh-meter-request.index')->with('success', 'KWH Meter Request deleted successfully.');
    }
}
