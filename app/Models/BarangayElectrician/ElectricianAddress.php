<?php

namespace App\Models\BarangayElectrician;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectricianAddress extends Model
{
    use HasFactory;

    protected $table = 'barangay_electrician_addresses';

    public function electrician()
    {
        return $this->belongsTo('App\Models\Electrician',  'electrician_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\Datamanagement\District', 'district_id', 'id');
    }

    public function municipality()
    {
        return $this->belongsTo('App\Models\Datamanagement\Municipality', 'municipality_id', 'id');
    }

    public function barangay()
    {
        return $this->belongsTo('App\Models\Datamanagement\Barangay', 'barangay_id', 'id');
    }
}
