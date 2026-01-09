<?php

namespace App\Http\Controllers;
use App\Models\Pending;
use App\Models\KwhMeterRequest;
use DB;
use Illuminate\Http\Request;
use App\Services\PendingTransactionService;

class PendingController extends Controller
{
    protected $pendingTransactionService;

    public function __construct(PendingTransactionService $pendingTransactionService)
    {
        $this->pendingTransactionService = $pendingTransactionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pending::where('recipient_user_id', auth()->id());
        
        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('transaction', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('table_name', 'LIKE', '%' . $searchTerm . '%');
                  
                // Handle status search by text (pending, approved, disapproved)
                if (stripos('pending', $searchTerm) !== false) {
                    $q->orWhere('status', 0)->orWhereNull('status');
                } elseif (stripos('approved', $searchTerm) !== false) {
                    $q->orWhere('status', 1);
                } elseif (stripos('disapproved', $searchTerm) !== false) {
                    $q->orWhere('status', 2);
                }
            });
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            if ($request->status !== 'all') {
                if ($request->status == '0') {
                    // For pending status, include both 0 and null values
                    $query->where(function($q) {
                        $q->where('status', 0)->orWhereNull('status');
                    });
                } else {
                    $query->where('status', $request->status);
                }
            }
        }
        
        $pendings = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());
        
        return view('layouts.pending', compact('pendings'));
    }

    /**
     * Search pending transactions
     */
    public function search(Request $request)
    {
        // Redirect to index with parameters - this maintains clean URLs
        return redirect()->route('pending.index', $request->only(['search', 'status']));
    }

    public function approve(Request $request)
    {
        
        try {
            DB::beginTransaction();
            $pending = Pending::find($request->input('pending_id'));

            if (!$pending) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pending transaction not found.'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Pending transaction not found.');
            }
            
            if ($pending->recipient_user_id != auth()->id()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to approve this transaction.'
                    ], 403);
                }
                return redirect()->back()->with('error', 'You are not authorized to approve this transaction.');
            }
            
            $pending->status = 1; // 1 = approved
            $pending->save();
            
            // update the transaction status
            if ($pending->table_name === 'kwh_meter_requests' && $pending->transaction === 'KWH Meter Request') {
                $kwhMeterRequest = KwhMeterRequest::find($pending->table_id);
                if ($kwhMeterRequest) {
                    $kwhMeterRequest->approved_at = now();
                    $kwhMeterRequest->save();
                }
            } else if ($pending->table_name === 'kwh_meter_requests' && $pending->transaction === 'KWH Meter Request Liquidation') {
                

                // check the approval step
                if ($pending->approval_step == 1) {
                    // find the related KWH Meter Request
                    $kwhMeterRequest = KwhMeterRequest::find($pending->table_id);

                    // add checked_by date and time for liquidation
                    if ($kwhMeterRequest) {
                        $kwhMeterRequest->checked_by = auth()->id();
                        $kwhMeterRequest->checked_at = now();
                        $kwhMeterRequest->save();
                    }

                    // add next approval step
                    //insert in to pending table
                    $this->pendingTransactionService->createPendingTransaction(
                        [
                            'transaction' => 'KWH Meter Request Liquidation',
                            'table_name' => 'kwh_meter_requests',
                            'url' => route('kwh-meter-request.show', $kwhMeterRequest->id),
                            'table_id' => $kwhMeterRequest->id,
                            'sender_user_id' => auth()->id(),
                            'recipient_user_id' => $kwhMeterRequest->approved_liquidation_by, // send to approver
                            'approval_step' => 2,
                            'status' => 0,
                        ]
                    );
                    
                }
                if ($pending->approval_step == 2) {
                    // find the related KWH Meter Request
                    $kwhMeterRequest = KwhMeterRequest::find($pending->table_id);

                    // add approved_liquidation_by date and time for liquidation
                    if ($kwhMeterRequest) {
                        $kwhMeterRequest->approved_liquidation_by = auth()->id();
                        $kwhMeterRequest->approved_liquidation_at = now();
                        $kwhMeterRequest->is_liquidated = true;
                        $kwhMeterRequest->save();
                    }
                    
                }

            }
            DB::commit();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaction approved successfully.',
                    'data' => $pending
                ]);
            }
            
            // return redirect()->back()->with('success', 'Transaction approved successfully.');
            
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to approve transaction: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to approve transaction: ' . $e->getMessage());
        }
    }

    public function disapprove(Request $request)
    {
        try {
            $pending = Pending::find($request->input('pending_id'));
            
            if (!$pending) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pending transaction not found.'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Pending transaction not found.');
            }
            
            if ($pending->recipient_user_id != auth()->id()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to disapprove this transaction.'
                    ], 403);
                }
                return redirect()->back()->with('error', 'You are not authorized to disapprove this transaction.');
            }
            
            $pending->status = 2; // 2 = disapproved
            $pending->save();

            // update the transaction status
            if ($pending->table_name === 'kwh_meter_requests') {
                $kwhMeterRequest = KwhMeterRequest::find($pending->table_id);
                if ($kwhMeterRequest) {
                    $kwhMeterRequest->disapproved_at = now();
                    $kwhMeterRequest->save();
                }
            }
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaction disapproved successfully.',
                    'data' => $pending
                ]);
            }
            
            return redirect()->back()->with('success', 'Transaction disapproved successfully.');
            
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to disapprove transaction: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to disapprove transaction: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics for pending transactions
     */
    public function getStatistics()
    {
        $userId = auth()->id();
        
        $stats = [
            'total' => Pending::where('recipient_user_id', $userId)->count(),
            'pending' => Pending::where('recipient_user_id', $userId)->where(function($q) {
                $q->where('status', 0)->orWhereNull('status');
            })->count(),
            'approved' => Pending::where('recipient_user_id', $userId)->where('status', 1)->count(),
            'disapproved' => Pending::where('recipient_user_id', $userId)->where('status', 2)->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Show pending transaction details for modal view
     */
    public function showDetails($id)
    {
        try {
            $pending = Pending::findOrFail($id);
            
            // Check if user is authorized to view this pending transaction
            if ($pending->recipient_user_id != auth()->id()) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to view this transaction.'
                    ], 403);
                }
                abort(403, 'Unauthorized');
            }

            // Extract information from the original URL to determine the view
            $url = $pending->url;
            $tableName = $pending->table_name;
            $tableId = $pending->table_id;

            // Based on the table name, load the appropriate data and view
            if ($tableName === 'kwh_meter_requests') {
                // Load KWH Meter Request data
                $kwhMeterRequest = \App\Models\KwhMeterRequest::with(['user', 'meterType', 'approvedBy'])->findOrFail($tableId);
                
                return view('layouts.pending_details', [
                    'pending' => $pending,
                    'data' => $kwhMeterRequest,
                    'type' => 'kwh_meter_request'
                ]);
            }
            // Add more cases for other transaction types as needed
            else {
                // Generic fallback - try to render basic pending info
                return view('layouts.pending_details', [
                    'pending' => $pending,
                    'data' => null,
                    'type' => 'generic'
                ]);
            }

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load transaction details: ' . $e->getMessage()
                ], 500);
            }
            
            return view('layouts.pending_details', [
                'pending' => null,
                'data' => null,
                'type' => 'error',
                'error' => $e->getMessage()
            ]);
        }
    }
}