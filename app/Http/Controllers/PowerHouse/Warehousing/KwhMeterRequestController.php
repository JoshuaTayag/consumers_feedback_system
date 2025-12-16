<?php

namespace App\Http\Controllers\PowerHouse\Warehousing;

use App\Http\Controllers\Controller;
use App\Models\KwhMeterRequest;
use App\Models\Pending;
use App\Models\User;
use App\Models\DataManagement\MeterType;
use DB;
use Illuminate\Http\Request;
use App\Helpers\Helper;

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

        $year = date("y");

        $control_id = Helper::IDGeneratorChangeMeter(new KwhMeterRequest, 'control_no', 5, $year, 'MR');

        DB::beginTransaction();
        try {
            $kwhMeterRequest = KwhMeterRequest::create([
                'user_id' => $validatedData['user_id'],
                'purpose' => $validatedData['purpose'],
                'meter_code_id' => $validatedData['meter_code_id'],
                'quantity' => $validatedData['quantity'],
                'approved_by' => $validatedData['approved_by'],
                'control_no' => $control_id,
                'is_liquidated' => false,
            ]);

            //insert in to pending table
            Pending::create([
                'transaction' => 'KWH Meter Request',
                'table_name' => 'kwh_meter_requests',
                'url' => route('kwh-meter-request.show',$kwhMeterRequest->id),
                'table_id' => $kwhMeterRequest->id,
                'sender_user_id' => $validatedData['user_id'],
                'recipient_user_id' => $validatedData['approved_by'],
                'approval_step' => 1,
                'status' => 0,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to create KWH Meter Request.'])->withInput();
        }

        return redirect()->route('kwh-meter-request.index')->with('success', 'KWH Meter Request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kwh_meter_request = KwhMeterRequest::with('kwhMeterRequestSerialNumbers')->findOrFail($id);
        $users = User::role(['TSD', 'TSD Manager'])->pluck('name', 'id');
        $meters_types = MeterType::get();
        return view('power_house.warehousing.kwh_meter_request.show', compact('kwh_meter_request', 'users', 'meters_types'));
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
            ->where('id', '!=', $id)
            ->count();

        if ($unliquidatedCount >= 2) {
            return redirect()->back()->withErrors(['user_id' => 'This user has unliquidated requests. Please liquidate them before submitting a new one.'])->withInput();
        }

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'purpose' => 'required|string|max:500',
            'meter_code_id' => 'required|exists:meter_types,id',
            'quantity' => 'required|integer|min:1|max:10',
            'approved_by' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();
        try {

            $kwh_meter_request = KwhMeterRequest::findOrFail($id);

            Pending::where('table_name', 'kwh_meter_requests')
                ->where('table_id', $kwh_meter_request->id)
                ->update([
                    'url' => route('kwh-meter-request.show',$id),
                    'sender_user_id' => $validatedData['user_id'],
                    'recipient_user_id' => $validatedData['approved_by'],
                ]);

            $kwh_meter_request->update([
                'user_id' => $validatedData['user_id'],
                'purpose' => $validatedData['purpose'],
                'meter_code_id' => $validatedData['meter_code_id'],
                'quantity' => $validatedData['quantity'],
                'approved_by' => $validatedData['approved_by'],
            ]);

            DB::commit();
            return redirect()->route('kwh-meter-request.index')->with('success', 'KWH Meter Request updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to update KWH Meter Request.'])->withInput();
        }
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

        if ($kwhMeterRequest->kwhMeterRequestSerialNumbers()->count() > 0) {
            return redirect()->route('kwh-meter-request.index')->withErrors(['error' => 'Cannot delete request with assigned meters.']);
        }

        DB::beginTransaction();
        try {
            Pending::where('table_name', 'kwh_meter_requests')
                ->where('table_id', $kwhMeterRequest->id)
                ->delete();
            $kwhMeterRequest->delete();
            DB::commit();
            return redirect()->route('kwh-meter-request.index')->with('success', 'KWH Meter Request deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kwh-meter-request.index')->withErrors(['error' => 'Failed to delete KWH Meter Request.']);
        }
       
    }


}
