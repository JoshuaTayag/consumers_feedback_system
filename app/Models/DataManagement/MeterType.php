<?php

namespace App\Models\DataManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeterType extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function kwhMeterRequests()
    {
        return $this->hasMany('App\Models\KwhMeterRequest', 'meter_code_id', 'id');
    }

    public function meters()
    {
        return $this->hasMany('App\Models\Meter', 'meter_type_id', 'id');
    }
    
    protected $fillable = [
        'meter_brand',
        'meter_code',
        'meter_description',
        'meter_type',
    ];
}
