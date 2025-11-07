<?php

namespace App\Models\BarangayElectrician;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectricianAccountDetails extends Model
{
    use HasFactory;

    protected $table = 'barangay_electrician_account_details';

    public function electrician()
    {
        return $this->belongsTo('App\Models\Electrician',  'electrician_id', 'id');
    }
}
