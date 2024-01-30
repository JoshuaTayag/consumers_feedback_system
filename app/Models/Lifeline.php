<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;


class Lifeline extends Model implements Auditable
{
    use HasFactory, SoftDeletes, HasRoles;
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

    public function consumer() {
        return $this->setConnection('sqlSrvBilling')->belongsTo('App\Models\ConsumersTable', 'account_no' , 'Accnt No'); 
    }

    protected $fillable = [
                            "control_no",
                            "first_name",
                            "middle_name",
                            "last_name",
                            "maiden_name",
                            "house_no_zone_purok_sitio",
                            "street",
                            "district_id",
                            "municipality_id",
                            "barangay_id",
                            "postal_code",
                            "date_of_birth",
                            "marital_status",
                            "contact_no",
                            "account_no",
                            "ownership",
                            "representative_id_no",
                            "representative_full_name",
                            "pppp_id",
                            "valid_id_no",
                            "valid_id_type",
                            "date_of_application",
                            "swdo_certificate_no",
                            "annual_income",
                            "validity_period_from",
                            "validity_period_to",
                            "application_status",
                            "approved_by",
                            "approved_date",
                            "remarks"
                          ];

    protected $dates = ['date_of_application'];
}
