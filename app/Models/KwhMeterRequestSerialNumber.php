<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class KwhMeterRequestSerialNumber extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function kwhMeterRequest()
    {
        return $this->belongsTo(KwhMeterRequest::class, 'kwh_meter_request_id', 'id');
    }

    public function meter()
    {
        return $this->belongsTo(Meter::class, 'meter_id', 'id');
    }

    public function changeMeterRequest()
    {
        return $this->belongsTo(ChangeMeterRequest::class, 'change_meter_request_id', 'id');
    }
    
    protected $fillable = [
        'meter_id',
        'kwh_meter_request_id',
        'change_meter_request_id',
        'status',
        'created_at',
        'updated_at'
    ];
}
