<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ChangeMeterRequestPostingHistory extends Model implements Auditable
{
    use HasFactory, SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    public function changeMeterRequest()
    {
        return $this->belongsTo('App\Models\ChangeMeterRequest', 'sco_no', 'control_no');
    }

    protected $table = 'posted_meters_history';

    protected $fillable = [
        "sco_no",
        "old_meter_no",
        "new_meter_no",
        "area",
        "process_date",
        "date_installed",
        "action_status",
        "posted_by",
        "feeder",
        "leyeco_seal_no",
        "serial_no",
        "erc_seal_no",
        "created_at",
        "updated_at",
        "deleted_at"
    ];
    
}
