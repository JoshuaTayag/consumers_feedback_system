<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChangeMeterRequestSlip extends Model implements Auditable
{
  use HasFactory, SoftDeletes;
  use \OwenIt\Auditing\Auditable;

  public function changeMeterRequest()
  {
    return $this->belongsTo(ChangeMeterRequest::class);
  }

  public function municipality()
  {
    return $this->belongsTo('App\Models\DataManagement\Municipality', 'municipality_id', 'id');
  }

  public function barangay()
  {
    return $this->belongsTo('App\Models\DataManagement\Barangay', 'barangay_id', 'id');
  }

  public function getCompleteNameAttribute()
  {
    return $this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name;
  }

  public function createdBy()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function getCreatedNameAttribute()
  {
    $user = $this->createdBy;

    if (!$user) {
      return null;
    }

    return $user->employee ? $user->employee->full_name : $user->name;
  }

  public function getAddressAttribute()
  {
    if ($this->municipality_id && $this->barangay_id) {
      $parts = array_filter([$this->sitio ? strtoupper($this->sitio) : null, 'Brgy. ' . $this->barangay->barangay_name, $this->municipality->municipality_name]);

      return implode(', ', $parts);
    }

    return 'No Address Available';
  }

  protected $fillable = ['first_name', 'middle_name', 'last_name', 'contact_no', 'account_number', 'meter_no', 'reading', 'reason_for_change_meter', 'landmark', 'change_meter_request_id', 'municipality_id', 'barangay_id', 'requested_by', 'sitio', 'control_no', 'rejection_remarks', 'created_by'];
}
