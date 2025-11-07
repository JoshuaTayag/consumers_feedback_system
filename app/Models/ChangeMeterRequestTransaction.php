<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


class ChangeMeterRequestTransaction extends Model implements Auditable
{
    use HasFactory, SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    public function changeMeterRequest()
    {
        return $this->belongsTo('App\Models\ChangeMeterRequest', 'change_meter_id', 'id');
    }

    protected $fillable = [
        "or_no",
        "change_meter_id",
        "total_fees",
        "total_amount_tendered",
        "change",
        "created_at"
    ];
}
