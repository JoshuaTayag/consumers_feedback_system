<?php

namespace App\Http\Controllers;
use App\Models\Meter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;

class MeterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $meters = Meter::orderBy('created_at', 'desc')->paginate(15);
        return view('power_house.warehousing.data_management.meters.index', compact('meters'));
    }

    public function create()
    {
        return view('power_house.warehousing.data_management.meters.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meter_brand' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:meters,serial_number',
            'erc_seal_number' => 'required|string|max:255|unique:meters,erc_seal_number',
            'leyeco_seal_number' => 'required|string|max:255|unique:meters,leyeco_seal_number',
            'control_type' => 'nullable|string|max:255',
            'control_no' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $meter = Meter::create($request->all());

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Meter created successfully',
                    'data' => $meter
                ], 201);
            }

            return redirect()->route('meters.index')->with('success', 'Meter created successfully');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create meter: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to create meter: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $meter = Meter::find($id);
        
        if (!$meter) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meter not found'
                ], 404);
            }
            return redirect()->route('meters.index')->with('error', 'Meter not found');
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $meter
            ]);
        }

        $audits = $meter->audits()->with('user')->orderBy('created_at', 'desc')->get();

        return view('power_house.warehousing.data_management.meters.show', compact('meter', 'audits'));
    }

    public function edit($id)
    {
        $meter = Meter::find($id);
        
        if (!$meter) {
            return redirect()->route('meters.index')->with('error', 'Meter not found');
        }

        return view('power_house.warehousing.data_management.meters.edit', compact('meter'));
    }

    public function update(Request $request, $id)
    {
        $meter = Meter::find($id);
        
        if (!$meter) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meter not found'
                ], 404);
            }
            return redirect()->route('meters.index')->with('error', 'Meter not found');
        }

        $validator = Validator::make($request->all(), [
            'meter_brand' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:meters,serial_number,' . $id,
            'erc_seal_number' => 'required|string|max:255|unique:meters,erc_seal_number,' . $id,
            'leyeco_seal_number' => 'required|string|max:255|unique:meters,leyeco_seal_number,' . $id,
            'control_type' => 'nullable|string|max:255',
            'control_no' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $meter->update($request->all());

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Meter updated successfully',
                    'data' => $meter
                ]);
            }

            return redirect()->route('meters.index')->with('success', 'Meter updated successfully');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update meter: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update meter: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $meter = Meter::find($id);
        
        if (!$meter) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meter not found'
                ], 404);
            }
            return redirect()->route('meters.index')->with('error', 'Meter not found');
        }

        try {
            DB::beginTransaction();

            $meter->delete(); // Soft delete due to SoftDeletes trait

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Meter deleted successfully'
                ]);
            }

            return redirect()->route('meters.index')->with('success', 'Meter deleted successfully');

        } catch (\Exception $e) {
            DB::rollback();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete meter: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to delete meter: ' . $e->getMessage());
        }
    }

    // API Methods
    public function validateSerialNumber(Request $request)
    {
        $serialNumber = $request->input('serial_number');
        $meterId = $request->input('meter_id'); // For updates
        
        $query = Meter::where('serial_number', $serialNumber);
        
        if ($meterId) {
            $query->where('id', '!=', $meterId);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? 'Serial number already exists' : 'Serial number is available'
        ]);
    }

    public function validateErcSeal(Request $request)
    {
        $ercSeal = $request->input('erc_seal_number');
        $meterId = $request->input('meter_id'); // For updates
        
        $query = Meter::where('erc_seal_number', $ercSeal);
        
        if ($meterId) {
            $query->where('id', '!=', $meterId);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? 'ERC seal number already exists' : 'ERC seal number is available'
        ]);
    }

    public function search(Request $request)
    {
        $query = Meter::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('meter_brand', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('leyeco_seal_number', 'like', "%{$search}%")
                  ->orWhere('erc_seal_number', 'like', "%{$search}%")
                  ->orWhere('control_no', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%");
            });
        }

        if ($request->expectsJson()) {
            // For AJAX requests, return all results (not paginated)
            $meters = $query->orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $meters
            ]);
        }

        // For regular requests, return paginated results
        $meters = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('power_house.warehousing.data_management.meters.index', compact('meters'));
    }

    public function getAuditLogs($id)
    {
        try {
            $meter = Meter::findOrFail($id);
            $audits = $meter->audits()->with('user')->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $audits,
                'total' => $audits->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch audit logs: ' . $e->getMessage()
            ], 500);
        }
    }
}
