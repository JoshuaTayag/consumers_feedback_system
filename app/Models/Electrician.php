<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

class Electrician extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

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

    public function electrician_complaints()
    {
        return $this->hasMany('App\Models\BarangayElectrician\ElectricianComplaint');
    }
    
    protected $fillable = [
        'control_number', 'first_name', 'middle_name', 'last_name', 'name_ext', 'sex',
        'civil_status', 'date_of_birth', 'email_address', 'fb_account', 'spouse_first_name',
        'spouse_middle_name', 'spouse_last_name', 'valid_id_type', 'valid_id_number',
        'application_type', 'application_status', 'tesda_course_title', 'tesda_name_of_school',
        'tesda_national_certificate_no', 'tesda_date_issued', 'tesda_valid_until_date',
        'rme_license_no', 'rme_issued_on', 'rme_issued_at', 'rme_valid_until',
        'ree_license_no', 'ree_issued_on', 'ree_issued_at', 'ree_valid_until',
        'remarks', 'application_status_remarks', 'date_of_application', 'created_by', 'primary_contact_no', 'secondary_contact_no',
    ];

}
