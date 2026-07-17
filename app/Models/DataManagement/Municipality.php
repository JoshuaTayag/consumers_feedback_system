<?php

namespace App\Models\DataManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Municipality extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $connection = 'sqlSrvMembership';
    protected $table = 'municipalities';
    protected $fillable = [
        'municipality_name',
        'district_id',
    ];

    public function barangay()
    {
        return $this->hasMany('App\Models\DataManagement\Barangay');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\DataManagement\District');
    }

    public function preMembership()
    {
        return $this->hasMany('App\Models\DataManagement\Premembership');
    }

    public function lifeline()
    {
        return $this->hasMany('App\Models\Lifeline');
    }

    public function mrf()
    {
        return $this->hasMany('App\Models\MaterialRequisitionForm');
    }

    public function electrician_address()
    {
        return $this->hasMany('App\Models\BarangayElectrician\ElectricianAddress');
    }
}
