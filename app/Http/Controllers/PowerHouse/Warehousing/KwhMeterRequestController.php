<?php

namespace App\Http\Controllers\PowerHouse\Warehousing;

use App\Http\Controllers\Controller;
use App\Models\KwhMeterRequest;
use App\Models\KwhMeterRequestSerialNumber;
use App\Models\Pending;
use App\Models\User;
use App\Models\DataManagement\MeterType;
use App\Services\PendingTransactionService;
use DB;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use PDF;
use App\Services\ChangeMeterService;

class KwhMeterRequestController extends Controller
{
    protected $pendingTransactionService;
    protected $changeMeterService;

    public function __construct(PendingTransactionService $pendingTransactionService, ChangeMeterService $changeMeterService)
    {
        $this->middleware('permission:kwh-meter-request-list|kwh-meter-request-create|kwh-meter-request-edit|kwh-meter-request-delete', ['only' => ['index']]);
        $this->middleware('permission:kwh-meter-request-create', ['only' => ['create', 'store', 'liquidate']]);
        $this->middleware('permission:kwh-meter-request-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:kwh-meter-request-delete', ['only' => ['destroy']]);

        $this->pendingTransactionService = $pendingTransactionService;
        $this->changeMeterService = $changeMeterService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kwh_meter_requests = KwhMeterRequest::orderBy('id', 'DESC')->paginate(15);
        $users = User::all(); // Fetch all users for liquidation modal
        return view('power_house.warehousing.kwh_meter_request.index', compact('kwh_meter_requests', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('name')->pluck('name', 'id');
        // Get meter types with available meter counts using service
        $type_of_meters = $this->changeMeterService->getMeterTypesWithAvailability();
        return view('power_house.warehousing.kwh_meter_request.create', compact('users', 'type_of_meters'));
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
        // if role is TSD add a maximum request count of 4 for unliquidated requests
        if ($unliquidatedCount >= env('TSD_KWH_METER_MAX_REQUESTS', 4) && auth()->user()->hasRole('TSD')) {
            return redirect()->back()->withErrors(['user_id' => 'You have unliquidated requests. Please liquidate them before submitting a new one.'])->withInput();
        }

        if ($request->quantity > env('TSD_MAX_METER_REQUEST', 10) && auth()->user()->hasRole('TSD')) {
            return redirect()->back()->withErrors(['quantity' => 'TSD can only request a maximum of ' . env('TSD_MAX_METER_REQUEST', 10) . ' meters per request.'])->withInput();
        }

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'purpose' => 'required|string|max:500',
            'meter_code_id' => 'required|exists:meter_types,id',
            'quantity' => 'required|integer|min:1',
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
            $this->pendingTransactionService->createKwhMeterRequestPending(
                $kwhMeterRequest->id,
                $validatedData['user_id'],
                $validatedData['approved_by']
            );

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
        $users = User::orderBy('name')->pluck('name', 'id');
        $meters_types = MeterType::get();
        
        // Get audit trail from pending table
        $audit_trail = Pending::where('table_name', 'kwh_meter_requests')
            ->where('table_id', $id)
            ->with(['senderUser', 'recipientUser'])
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('power_house.warehousing.kwh_meter_request.show', compact('kwh_meter_request', 'users', 'meters_types', 'audit_trail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kwh_meter_request = KwhMeterRequest::findOrFail($id);
        $users = User::orderBy('name')->pluck('name', 'id');
        $type_of_meters = $this->changeMeterService->getMeterTypesWithAvailability(null, $id);
        return view('power_house.warehousing.kwh_meter_request.edit', compact('kwh_meter_request', 'users', 'type_of_meters'));
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

            $this->pendingTransactionService->updateKwhMeterRequestPending(
                $kwh_meter_request->id,
                $validatedData['user_id'],
                $validatedData['approved_by']
            );

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
            $this->pendingTransactionService->deletePendingTransaction(
                'kwh_meter_requests',
                $kwhMeterRequest->id
            );
            $kwhMeterRequest->delete();
            DB::commit();
            return redirect()->route('kwh-meter-request.index')->with('success', 'KWH Meter Request deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kwh-meter-request.index')->withErrors(provider: ['error' => 'Failed to delete KWH Meter Request.']);
        }
       
    }

    public function generateKwhMeterReport(){
        $requesitioners = User::role(['TSD', 'TSD Manager'])->get();
        return view('power_house.warehousing.kwh_meter_request.report')->with(compact('requesitioners'));
    }

    public function KwhMeterPdfReport(Request $request)
    {
        $datas = KwhMeterRequestSerialNumber::with('kwhMeterRequest', 'meter')
        ->whereHas('kwhMeterRequest', function($query) use ($request) {
            $query->where('user_id', $request->input('requesitioner'));
            $query->where('created_at', '>=', $request->input('date_from'));
            $query->where('created_at', '<=', $request->input('date_to'));
        })
        ->orderBy('id', 'DESC')
        ->get();
        $requesitioner = User::find($request->input('requesitioner'));
        $pdf = PDF::loadView('power_house.warehousing.kwh_meter_request.pdf_report', compact('datas', 'requesitioner'));
        $pdf->setPaper('legal', 'landscape');
        return $pdf->stream();;
    }

    /**
     * Handle liquidation of KWH Meter Request
     */
    public function liquidate(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'checked_by' => 'required|exists:users,id',
            'audited_by' => 'required|exists:users,id',
            'approved_by_liquidation' => 'required|exists:users,id',
            'liquidation_remarks' => 'nullable|string|max:500',
        ]);

        $kwhMeterRequest = KwhMeterRequest::findOrFail($id);
        $pendingTransaction = Pending::where('table_name', 'kwh_meter_requests')
                ->where('table_id', $kwhMeterRequest->id)
                ->where('status', 0) // pending status
                ->first();

        // Check if the request can be liquidated
        if ($kwhMeterRequest->is_liquidated) {
            return redirect()->route('kwh-meter-request.index')
                ->withErrors(['error' => 'This request has already been liquidated.']);
        }

        if (!$kwhMeterRequest->approved_at) {
            return redirect()->route('kwh-meter-request.index')
                ->withErrors(['error' => 'This request must be approved before liquidation.']);
        }

        if ($kwhMeterRequest->getLiquidationProgress()['progress_percentage'] < 100) {
            return redirect()->route('kwh-meter-request.index')
                ->withErrors(['error' => 'This request is not yet ready for liquidation. Please ensure all meters are assigned.']);
        }

        if ($pendingTransaction) {
            return redirect()->route('kwh-meter-request.index')
                ->withErrors(['error' => 'Liquidation is already pending approval for this request.']);
        }

        DB::beginTransaction();
        try {
            // Update the KWH Meter Request as liquidated
            $kwhMeterRequest->update([
                // 'is_liquidated' => true,
                'liquidated_at' => now(),
                'checked_by' => $validatedData['checked_by'],
                'approved_liquidation_by' => $validatedData['approved_by_liquidation'],
                'audited_by' => $validatedData['audited_by'],
                'liquidation_remarks' => $validatedData['liquidation_remarks'],
            ]);

            // Update the pending transaction status to approved/completed
            $this->pendingTransactionService->createPendingTransaction(
                [
                    'transaction' => 'KWH Meter Request Liquidation',
                    'table_name' => 'kwh_meter_requests',
                    'url' => route('kwh-meter-request.show', $kwhMeterRequest->id),
                    'table_id' => $kwhMeterRequest->id,
                    'sender_user_id' => $kwhMeterRequest->user_id,
                    'recipient_user_id' => $validatedData['checked_by'],
                    'approval_step' => 1, // first step of liquidation approval
                    'status' => 0,
                ]
            );

            DB::commit();
            return redirect()->route('kwh-meter-request.index')
                ->with('success', 'KWH Meter Request has been successfully liquidated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kwh-meter-request.index')
                ->withErrors(['error' => 'Failed to liquidate KWH Meter Request: ' . $e->getMessage()]);
        }
    }


}
