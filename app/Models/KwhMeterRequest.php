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

    protected $fillable = [
        'user_id',
        'meter_code_id',
        'quantity',
        'purpose',
        'status',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by'
    ];
}
