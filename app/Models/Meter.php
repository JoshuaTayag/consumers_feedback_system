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

    protected $fillable = ['meter_type_id', 
                        'serial_number',
                        'leyeco_seal_number',
                        'erc_seal_number',
                        'control_type',
                        'control_no',
                        'account_number',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ];
}
