<?php

namespace App\Models\DataManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Barangay extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    
    protected $connection = 'sqlSrvMembership';
    protected $table = 'barangays';
    protected $fillable = [
        'barangay_name',
        'municipality_id',
    ];

    public function municipality()
    {
        return $this->belongsTo('App\Models\Datamanagement\Municipality');
    }

    public function preMembership()
    {
        return $this->hasMany('App\Models\Premembership');
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
