<?php

namespace App\Http\Controllers;

use App\Models\ChangeMeterRequestSlip;
use Illuminate\Http\Request;
use App\Models\DataManagement\Area;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Models\DataManagement\KwhMeterDamageCauseType;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ChangeMeterRequestSlipController extends Controller
{
  public function __construct()
  {
    $this->middleware('permission:change-meter-request-slip-list|change-meter-request-slip-create|change-meter-request-slip-edit|change-meter-request-slip-delete', ['only' => ['index', 'search']]);
    $this->middleware('permission:change-meter-request-slip-create', ['only' => ['create', 'store']]);
    $this->middleware('permission:change-meter-request-slip-edit', ['only' => ['edit', 'update']]);
    $this->middleware('permission:change-meter-request-slip-delete', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $change_meter_request_slips = ChangeMeterRequestSlip::with(['municipality', 'barangay', 'createdBy.employee'])
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    $slip_status_count = $this->getSlipStatusCounts();

    return view('service_connect_order.change_meter.request_slip.index', compact('change_meter_request_slips', 'slip_status_count'));
  }

  public function search(Request $request)
  {
    $slips = ChangeMeterRequestSlip::with(['municipality', 'barangay', 'createdBy.employee']);

    if ($request->filled('search')) {
      $searchTerm = $request->search;
      $slips->where(function ($q) use ($searchTerm) {
        $q->where('control_no', 'LIKE', '%' . $searchTerm . '%')
          ->orWhere('first_name', 'LIKE', '%' . $searchTerm . '%')
          ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%')
          ->orWhere('account_number', 'LIKE', '%' . $searchTerm . '%')
          ->orWhere('meter_no', 'LIKE', '%' . $searchTerm . '%');
      });
    }

    if ($request->filled('status')) {
      $slips->where('status', $request->status);
    }

    $change_meter_request_slips = $slips->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
    $slip_status_count = $this->getSlipStatusCounts();

    return view('service_connect_order.change_meter.request_slip.index', compact('change_meter_request_slips', 'slip_status_count'));
  }

  // Status counts for the dashboard cards
  private function getSlipStatusCounts()
  {
    $today = now()->startOfDay();
    $yesterday = now()->subDay()->startOfDay();

    return [
      'total' => [
        'pending' => ChangeMeterRequestSlip::where('status', 1)->count(),
        'accepted' => ChangeMeterRequestSlip::where('status', 2)->count(),
        'rejected' => ChangeMeterRequestSlip::where('status', 3)->count(),
      ],
      'today' => [
        'pending' => ChangeMeterRequestSlip::where('status', 1)->whereDate('created_at', $today)->count(),
        'accepted' => ChangeMeterRequestSlip::where('status', 2)->whereDate('created_at', $today)->count(),
        'rejected' => ChangeMeterRequestSlip::where('status', 3)->whereDate('created_at', $today)->count(),
      ],
      'yesterday' => [
        'pending' => ChangeMeterRequestSlip::where('status', 1)->whereDate('created_at', $yesterday)->count(),
        'accepted' => ChangeMeterRequestSlip::where('status', 2)->whereDate('created_at', $yesterday)->count(),
        'rejected' => ChangeMeterRequestSlip::where('status', 3)->whereDate('created_at', $yesterday)->count(),
      ],
      'old_transactions' => [
        'pending' => ChangeMeterRequestSlip::where('status', 1)->whereDate('created_at', '<', $yesterday)->count(),
        'accepted' => ChangeMeterRequestSlip::where('status', 2)->whereDate('created_at', '<', $yesterday)->count(),
        'rejected' => ChangeMeterRequestSlip::where('status', 3)->whereDate('created_at', '<', $yesterday)->count(),
      ],
    ];
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $areas = Area::with('feeders:id,area_id,feeder_name')->orderBy('id')->get();
    $municipalities = DB::connection('sqlSrvMembership')->table('municipalities')->select('*')->orderBy('municipality_name', 'asc')->get();
    $employees = DB::connection('prismSystemConnection')->table('employee')->select(DB::raw("CONCAT(lastname, ', ', firstname, ' ', LEFT(middlename, 1), '.') as fullname"))->orderBy('lastname', 'asc')->get();
    $damageCauses = KwhMeterDamageCauseType::select('id', 'name')->orderBy('name', 'asc')->get();
    return view('service_connect_order.change_meter.request_slip.create', compact('areas', 'municipalities', 'employees', 'damageCauses'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // dd($request->all());
    $validatedData = $request->validate([
      'account_number' => 'required|string|max:255|unique:change_meter_request_slips,account_number,NULL,id,deleted_at,NULL',
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'contact_no' => ['nullable', 'regex:/^((09))[0-9]{9}/', 'digits:11'],
      'municipality_id' => 'required|integer',
      'barangay_id' => 'required|integer',
      'sitio' => 'required|string|max:255',
      'landmark' => 'nullable|string|max:255',
      'meter_no' => 'required|string|max:255',
      'reading' => 'nullable|string|max:255',
      'requested_by' => 'required',
      'damage_cause' => 'required',
    ]);

    $year = date('y');
    $control_id = Helper::IDGeneratorChangeMeter(new ChangeMeterRequestSlip(), 'control_no', 5, $year, 'CMS');

    $validatedData['created_by'] = Auth::id();
    $validatedData['control_no'] = $control_id;
    $validatedData['reason_for_change_meter'] = $request->input('damage_cause');

    ChangeMeterRequestSlip::create($validatedData);

    return redirect()->route('change-meter-request-slip.index')->with('success', 'Change Meter Request Slip created successfully.');
  }

  /**
   * Display the specified resource.
   */
  public function show(ChangeMeterRequestSlip $changeMeterRequestSlip)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(ChangeMeterRequestSlip $changeMeterRequestSlip)
  {
    $areas = Area::with('feeders:id,area_id,feeder_name')->orderBy('id')->get();
    $municipalities = DB::connection('sqlSrvMembership')->table('municipalities')->select('*')->orderBy('municipality_name', 'asc')->get();
    $change_meter_request_slip = ChangeMeterRequestSlip::find($changeMeterRequestSlip->id);
    $employees = DB::connection('prismSystemConnection')->table('employee')->select(DB::raw("CONCAT(lastname, ', ', firstname, ' ', LEFT(middlename, 1), '.') as fullname"))->orderBy('lastname', 'asc')->get();
    $damageCauses = KwhMeterDamageCauseType::select('id', 'name')->orderBy('name', 'asc')->get();
    return view('service_connect_order.change_meter.request_slip.edit', compact('change_meter_request_slip', 'areas', 'municipalities', 'employees', 'damageCauses'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, ChangeMeterRequestSlip $changeMeterRequestSlip)
  {
    $validatedData = $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'contact_no' => ['nullable', 'regex:/^((09))[0-9]{9}/', 'digits:11'],
      'municipality_id' => 'required|integer',
      'barangay_id' => 'required|integer',
      'sitio' => 'required|string|max:255',
      'landmark' => 'nullable|string|max:255',
      'meter_no' => 'required|string|max:255',
      'reading' => 'nullable|string|max:255',
      'requested_by' => 'required',
      'damage_cause' => 'required',
    ]);

    $validatedData['reason_for_change_meter'] = $validatedData['damage_cause'];
    $changeMeterRequestSlip->update($validatedData);

    return redirect()->route('change-meter-request-slip.index')->with('success', 'Change Meter Request Slip updated successfully.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(ChangeMeterRequestSlip $changeMeterRequestSlip)
  {
    //check first if slip is accepted or processed before deleting
    if ($changeMeterRequestSlip->status != 1 || $changeMeterRequestSlip->change_meter_request_id != null) {
      return redirect()->route('change-meter-request-slip.index')->with('error', 'Only pending slips and those not yet associated with a change meter request can be deleted.');
    }

    $changeMeterRequestSlip->delete();
    return redirect()->route('change-meter-request-slip.index')->with('success', 'Change Meter Request Slip deleted successfully.');
  }

  public function checkAccount(Request $request)
  {
    $slip = ChangeMeterRequestSlip::where('account_number', $request->account_number)->first();

    return response()->json([
      'exists' => (bool) $slip,
      'message' => $slip ? 'This account number already has a change meter request slip.' : null,
    ]);
  }

  public function acceptChangeMeterRequestSlip(Request $request)
  {
    $slip = ChangeMeterRequestSlip::where('account_number', $request->account_number)->first();

    return response()->json([
      'exists' => (bool) $slip,
      'message' => $slip ? 'This account number already has a change meter request slip.' : null,
    ]);
  }

  public function rejectChangeMeterRequestSlip(Request $request)
  {
    // dd($request);
    $changeMeterSlip = ChangeMeterRequestSlip::find($request->request_slip_id);
    $changeMeterSlip->status = 3; // Set status to rejected
    $changeMeterSlip->rejection_remarks = $request->rejection_reason;
    $changeMeterSlip->save();

    return redirect()->route('change-meter-request-slip.index')->with('success', 'Change Meter Request Slip updated successfully.');
  }

  public function fetchSlip($id)
  {
    $slip = ChangeMeterRequestSlip::with(['municipality', 'barangay', 'changeMeterRequest.area', 'changeMeterRequest.feeder', 'changeMeterRequest.municipality', 'changeMeterRequest.barangay'])->find($id);

    if (!$slip) {
      return response()->json(['error' => 'Slip not found'], 404);
    }

    return response()->json(['slip' => $slip]);
  }

  public function viewReport(Request $request)
  {
    $municipalities = DB::connection('sqlSrvMembership')->table('municipalities')->select('*')->orderBy('municipality_name', 'asc')->get();

    $damage_meter_causes = KwhMeterDamageCauseType::pluck('name', 'id');

    return view('service_connect_order.change_meter.request_slip.report', compact('municipalities', 'damage_meter_causes'));
  }

  public function generateChangeMeterRequestSlipReport(Request $request)
  {
    // Start building the query
    $query = ChangeMeterRequestSlip::query();

    if ($request->app_status == 1 && $request->app_status !== null) {
      $query->where('status', 1); // pending
      $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
    }

    if ($request->app_status == 2 && $request->app_status !== null) {
      $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
      $query->where('status', 2); // accepted
    }

    if ($request->app_status == 3 && $request->app_status !== null) {
      $query->where('status', 3); // rejected
      $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
    }

    if ($request->municipality) {
      $query->where('municipality_id', $request->municipality);
    }

    if ($request->barangay) {
      $query->where('barangay_id', $request->barangay);
    }

    if ($request->damage_cause) {
      $query->where('reason_for_change_meter', $request->damage_cause);
    }

    // Execute the query and get the results
    $change_meter_requests = $query->get();

    view()->share('datas', $change_meter_requests);
    $pdf = PDF::loadView('service_connect_order.change_meter.request_slip.pdf_report')->setPaper('legal', 'landscape');
    return $pdf->stream();
  }
}
