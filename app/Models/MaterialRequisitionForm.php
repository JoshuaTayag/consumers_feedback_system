<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequisitionForm extends Model
{
    use HasFactory;

    public function getRequestedNameAttribute()
    {
        $user = User::where('id', $this->requested_id)
            ->get();
        $employee = $user[0]->employee;
        if($employee){
            return $employee->prefix . " " . $employee->first_name . " " . substr($employee->middle_name, 0, 1). "." . " " . $employee->last_name . " " . $employee->suffix;
        }
        return $user[0]->name;
    }

    public function getApprovedNameAttribute()
    {
        $user = User::where('id', $this->approved_id)
            ->get();
        $employee = $user[0]->employee;
        if($employee){
            return $employee->prefix . " " . $employee->first_name . " " . substr($employee->middle_name, 0, 1). "." . " " . $employee->last_name . " " . $employee->suffix;
        }
        return $user[0]->name;
    }

    public function getProcessedNameAttribute()
    {
        $user = User::where('id', $this->processed_id)
            ->get();
        $employee = $user[0]->employee;
        if($employee){
            return $employee->prefix . " " . $employee->first_name . " " . substr($employee->middle_name, 0, 1). "." . " " . $employee->last_name . " " . $employee->suffix;
        }
        return $user[0]->name;
    }

    public function getRequestTypeAssigneeNameAttribute()
    {
        $user = User::where('id', $this->req_type_assignee)
            ->get();
        $employee = $user[0]->employee;
        if($employee){
            return $employee->prefix . " " . $employee->first_name . " " . substr($employee->middle_name, 0, 1). "." . " " . $employee->last_name . " " . $employee->suffix;
        }
        return $user[0]->name;
    }

    public function items()
    {
        return $this->hasMany('App\Models\MaterialRequisitionFormItems');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District', 'district_id', 'id');
    }

    public function municipality()
    {
        return $this->belongsTo('App\Models\Municipality', 'municipality_id', 'id');
    }

    public function barangay()
    {
        return $this->belongsTo('App\Models\Barangay', 'barangay_id', 'id');
    }

    public function user_requested()
    {
        return $this->belongsTo('App\Models\User', 'requested_id', 'id');
    }

    public function user_approved()
    {
        return $this->belongsTo('App\Models\User', 'approved_id', 'id');
    }

    public function request_type_assignee()
    {
        return $this->belongsTo('App\Models\User', 'req_type_assignee', 'id');
    }

    public function mrf_liquidations()
    {
        return $this->hasMany('App\Models\MaterialRequisitionFormLiquidation');
    }

    
    
    protected $fillable = [ 'project_name', 
                            'district_id', 
                            'municipality_id', 
                            'barangay_id', 
                            'sitio', 
                            'remarks',
                            'status',
                            'requested_id',
                            'requested_by',
                            'approved_id',
                            'approved_by',
                            'processed_id',
                            'processed_by',
                            'released_id',
                            'released_by',
                            'liquidated_id',
                            'liquidated_by',
                            'area_id',
                          ];
}
