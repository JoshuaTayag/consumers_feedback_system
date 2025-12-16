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

    protected $fillable = [
        'user_id',
        'meter_code_id',
        'control_no',
        'quantity',
        'purpose',
        'approved_by',
        'is_liquidated',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_liquidated' => 'boolean',
    ];
}
