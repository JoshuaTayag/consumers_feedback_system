<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransactionFees extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        "payment_transaction_id",
        "type_of_fees",
        "amount_of_fees",
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    public function payment_transaction()
    {
        return $this->belongsTo('App\Models\PaymentTransaction');
    }

}
