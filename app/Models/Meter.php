<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use App\Models\DataManagement\MeterType;

class Meter extends Model implements Auditable
{
    use HasFactory, SoftDeletes, HasRoles;
    use \OwenIt\Auditing\Auditable;

    public function meterType()
    {
        return $this->belongsTo(MeterType::class);
    }

    public function changeMeterRequest()
    {
        return $this->belongsTo('App\Models\ChangeMeterRequest', 'control_no', 'control_no');
    }

    public function kwhMeterRequestSerialNumbers()
    {
        return $this->hasMany(KwhMeterRequestSerialNumber::class, 'meter_id', 'id');
    }

    // Helper method to get current KWH meter request assignment
    public function currentKwhMeterRequest()
    {
        return $this->hasOneThrough(
            KwhMeterRequest::class,
            KwhMeterRequestSerialNumber::class,
            'meter_id',
            'id',
            'id',
            'kwh_meter_request_id'
        );
    }



    protected $fillable = ['meter_type_id', 
                        'serial_number',
                        'leyeco_seal_number',
                        'erc_seal_number',
                        'control_type',
                        'control_no',
                        'account_number',
                        'status',
                        'kwh_meter_request_id',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ];
}
