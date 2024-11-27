<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChangeMeterRequest extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function getCreatedNameAttribute()
    {
        $user = User::where('id', $this->created_by)
            ->get();
        $employee = $user[0]->employee;
        if($employee){
            return $employee->prefix . " " . $employee->first_name . " " . substr($employee->middle_name, 0, 1). "." . " " . $employee->last_name . " " . $employee->suffix;
        }
        return $user[0]->name;
    }

    public function municipality()
    {
        return $this->belongsTo('App\Models\Municipality', 'municipality_id', 'id');
    }

    public function barangay()
    {
        return $this->belongsTo('App\Models\Barangay', 'barangay_id', 'id');
    }

    public function cmr_fees()
    {
        return $this->hasMany('App\Models\ChangeMeterRequestFees', 'cm_control_no', 'id');
    }

    public function postedMeterHistory()
    {
        return $this->hasOne('App\Models\ChangeMeterRequestPostingHistory' , 'sco_no', 'control_no');
    }

    public function changeMeterRequestTransaction()
    {
        return $this->hasOne('App\Models\ChangeMeterRequestTransaction' , 'change_meter_id', 'id');
    }

    protected $fillable = [
        'control_no', 'first_name', 'middle_name', 'last_name', 'contact_no',
        'area', 'municipality_id', 'barangay_id', 'sitio', 'account_number',
        'care_of', 'feeder', 'membership_or', 'consumer_type', 'old_meter_no',
        'meter_or_number', 'meter_or_date', 'new_meter_no', 'type_of_meter',
        'last_reading', 'initial_reading', 'remarks', 'location', 'crew',
        'date_time_acted', 'status', 'damage_cause', 'crew_remarks', 'created_by',
        'created_at', 'process_date', 'dispatched_date'
    ];
}
