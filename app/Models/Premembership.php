<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Premembership extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

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

    protected $connection = 'sqlSrvMembership';
    protected $table = 'pre_membership';
    protected $fillable = ['first_name', 
                        'middle_name', 
                        'last_name', 
                        'spouse', 
                        'joint', 
                        'single', 
                        'date_of_birth', 
                        'contact_no', 
                        'district_id', 
                        'municipality_id', 
                        'barangay_id', 
                        'sitio', 
                        'date_and_time_conducted', 
                        'pms_conductor', 
                        'place_conducted',
                        'juridical'
                    ];
}
