<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;

class ServiceConnectOrder extends Model implements Auditable
{
    use HasFactory, SoftDeletes, HasRoles;
    use \OwenIt\Auditing\Auditable;

    protected $connection = 'sqlSrvHousewiring';
    protected $table = 'Service Connect Table';
    protected $primaryKey = 'application_id';

    protected $fillable = ['SCONo', 
                        'Lastname'
                    ];

}
