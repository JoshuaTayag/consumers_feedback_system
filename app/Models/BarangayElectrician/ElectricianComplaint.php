<?php

namespace App\Models\BarangayElectrician;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ElectricianComplaint extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'barangay_electrician_complaints';

    public function electrician()
    {
        return $this->belongsTo('App\Models\Electrician',  'electrician_id', 'id');
    }

    // Allow mass assignment for these attributes
    protected $fillable = [
        'control_number',
        'date',
        'complainant_name',
        'electrician_id',
        'act_of_misconduct',
        'remarks',
        'nature_of_complaint',
        'other_nature_of_complaint',
        'sanction_type',
        'revocation_date',
        'date_of_suspension_from',
        'date_of_suspension_to',
        'status_of_complaint',
        'status_explanation',
        'sanction_remarks',
        'file_path',
        'complainant_contact_no',
        'complainant_district_id',
        'complainant_municipality_id',
        'complainant_barangay_id',
    ];
}
