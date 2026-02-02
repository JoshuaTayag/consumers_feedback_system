<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function employee()
    {
        return $this->hasOne('App\Models\Employee');
    }

    public function requested_mrf()
    {
        return $this->hasMany('App\Models\MaterialRequisitionForm', 'requested_id', 'id');
    }

    public function approved_mrf()
    {
        return $this->hasMany('App\Models\MaterialRequisitionForm');
    }
    
    public function change_meter_contractor()
    {
        return $this->hasOne('App\Models\ChangeMeterRequestContractor', 'user_id', 'id');
    }   

    public function kwhMeterRequests()
    {
        return $this->hasMany('App\Models\KwhMeterRequest', 'user_id', 'id');
    }
    
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
