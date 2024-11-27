<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransaction extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        "or_no",
        "control_no",
        "first_name",
        "last_name",
        "municipality_id",
        "barangay_id",
        "sitio",
        "business_type_id",
        "consumer_type_id",
        "cheque_no",
        "cheque_date",
        "process_date",
        "created_at",
        "processed_by"
    ];

    public function payment_transaction_fees()
    {
        return $this->hasMany('App\Models\PaymentTransactionFees', 'cm_control_no', 'id');
    }

    public function municipality()
    {
        return $this->belongsTo('App\Models\Municipality', 'municipality_id', 'id');
    }

    public function barangay()
    {
        return $this->belongsTo('App\Models\Barangay', 'barangay_id', 'id');
    }
}
