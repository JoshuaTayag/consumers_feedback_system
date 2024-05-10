<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agmm extends Model
{
    use HasFactory;

    protected $fillable = ['account_no', 
                        'first_name', 
                        'middle_name', 
                        'last_name', 
                        'contact_no',
                        'membership_or',
                        'registration_type',
                        'qr_code_value',
                        'transpo_allowance',
                        'allowance_status',
                    ];
}
