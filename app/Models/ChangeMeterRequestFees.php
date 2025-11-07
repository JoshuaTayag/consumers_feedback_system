<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;


class ChangeMeterRequestFees extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function change_meter_request()
    {
        return $this->belongsTo('App\Models\ChangeMeterRequest');
    }

    protected $table = 'change_meter_fees';

    protected $fillable = [
        'cm_control_no', 'fees', 'amount', // Add these fields to allow mass assignment
    ];
}
