<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChangeMeterRequestContractor extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function getEmailAccountAttribute()
    {
        if ($this->user) {
            return $this->user->email;
        }
        return 'No Email Account Assigned';
    }

    public function getFullNameAttribute()
    {
        if ($this->first_name && $this->last_name) {
            return $this->last_name . ', ' . $this->first_name;
        }
        return 'No Name Available';
    }

    public function change_meter_request()
    {
        return $this->belongsTo('App\Models\ChangeMeterRequest');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    protected $table = 'change_meter_contractors';

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'mobile_number',
        'user_id'
    ];
}
