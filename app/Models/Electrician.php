<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Electrician extends Model
{
    use HasFactory;

    protected $table = 'barangay_electricians';

    public function electrician_accounts()
    {
        return $this->hasMany('App\Models\BarangayElectrician\ElectricianAccountDetails');
    }

    public function electrician_addresses()
    {
        return $this->hasMany('App\Models\BarangayElectrician\ElectricianAddress');
    }

    public function electrician_contact_numbers()
    {
        return $this->hasMany('App\Models\BarangayElectrician\ElectricianContactNumber');
    }

    public function electrician_educational_backgrounds()
    {
        return $this->hasMany('App\Models\BarangayElectrician\ElectricianEducationalBackground');
    }
    
}
