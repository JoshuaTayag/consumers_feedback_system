<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumersTable extends Model
{
    use HasFactory;

    protected $connection = 'sqlSrvBilling';
    protected $table = 'Consumers Table';
    protected $primaryKey = 'Accnt No';
    protected $keyType = 'string';
    public $incrementing = false;

    public function lifeline()
    {
        return $this->setConnection('sqlsrv')->hasOne('App\Models\Lifeline', 'account_no', 'Accnt No');
    }
}
