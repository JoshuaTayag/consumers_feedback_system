<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\DataManagement\MeterType;

class KwhMeterRequest extends Model implements Auditable
{
    use HasFactory, SoftDeletes, HasRoles;
    use \OwenIt\Auditing\Auditable;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function meterType()
    {
        return $this->belongsTo(MeterType::class, 'meter_code_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function liquidatedBy()
    {
        return $this->belongsTo(User::class, 'approved_liquidation_by');
    }

    public function kwhMeterRequestSerialNumbers()
    {
        return $this->hasMany(KwhMeterRequestSerialNumber::class, 'kwh_meter_request_id');
    }

    public function changeMeterRequests()
    {
        return $this->hasMany(ChangeMeterRequest::class, 'kwh_meter_request_id');
    }

    // Helper method to get assigned meters through the tracking table
    public function assignedMeters()
    {
        return $this->hasManyThrough(
            Meter::class,
            KwhMeterRequestSerialNumber::class,
            'kwh_meter_request_id',
            'id',
            'id',
            'meter_id'
        );
    }

    // Helper method to check remaining meters that can be assigned
    public function getRemainingQuantityAttribute()
    {
        return $this->quantity - $this->kwhMeterRequestSerialNumbers()->count();
    }

    /**
     * Check if the KWH meter request should be marked as liquidated
     * and update status if conditions are met
     */
    public function checkAndUpdateLiquidationStatus()
    {
        // Get all assigned serial numbers
        $assignedSerials = $this->kwhMeterRequestSerialNumbers();
        $totalAssigned = $assignedSerials->count();
        $liquidatedCount = $assignedSerials->where('status', 1)->count();
        $pendingCount = $assignedSerials->where(function($query) {
            $query->whereNull('status')->orWhere('status', 0);
        })->count();
        
        // Check conditions
        $quantityMet = $totalAssigned >= $this->quantity;
        $allLiquidated = $pendingCount == 0;
        
        $result = [
            'should_liquidate' => $quantityMet && $allLiquidated,
            'quantity_met' => $quantityMet,
            'all_liquidated' => $allLiquidated,
            'requested_quantity' => $this->quantity,
            'total_assigned' => $totalAssigned,
            'liquidated_count' => $liquidatedCount,
            'pending_count' => $pendingCount
        ];
        
        // Update status if conditions are met
        if ($result['should_liquidate'] && !$this->is_liquidated) {
            $this->update([
                'is_liquidated' => true,
            ]);
            
            $result['status_updated'] = true;
            
            \Log::info("KWH Meter Request {$this->id} automatically liquidated", $result);
        } else {
            $result['status_updated'] = false;
        }
        
        return $result;
    }

    /**
     * Get the current liquidation progress
     */
    public function getLiquidationProgress()
    {
        $assignedSerials = $this->kwhMeterRequestSerialNumbers();
        $totalAssigned = $assignedSerials->count();
        $liquidatedCount = $assignedSerials->where('status', 1)->count();
        
        return [
            'requested_quantity' => $this->quantity,
            'total_assigned' => $totalAssigned,
            'liquidated_count' => $liquidatedCount,
            'remaining_count' => max(0, $this->quantity - $liquidatedCount),
            'progress_percentage' => $this->quantity > 0 ? round(($liquidatedCount / $this->quantity) * 100, 2) : 0,
            'is_complete' => $liquidatedCount >= $this->quantity && $assignedSerials->where(function($query) {
                $query->whereNull('status')->orWhere('status', 0);
            })->count() == 0
        ];
    }

    protected $fillable = [
        'user_id',
        'meter_code_id',
        'control_no',
        'quantity',
        'purpose',
        'approved_by',
        'is_liquidated',
        'liquidated_at',
        'checked_by',
        'checked_at',
        'approved_liquidation_by',
        'approved_liquidation_at',
        'liquidation_remarks',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_liquidated' => 'boolean',
        'approved_at' => 'datetime',
        'liquidated_at' => 'datetime',
        'disapproved_at' => 'datetime', // if you have this column too
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'checked_at' => 'datetime',
        'approved_liquidation_at' => 'datetime',
    ];
}
