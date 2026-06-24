<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountLedger extends Model
{
    use HasFactory;
    protected $table = 'Consumers Table';
    protected $primaryKey = 'Accnt No';
    protected $keyType = 'string';
    public $incrementing = false;
}
