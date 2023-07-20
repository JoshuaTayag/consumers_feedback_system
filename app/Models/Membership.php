<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Membership extends Model implements Auditable
{
    use HasFactory, SoftDeletes, HasRoles;
    use \OwenIt\Auditing\Auditable;

    public function setDateAttribute( $value ) {
        $this->attributes['OR No Issued'] = (new Carbon($value))->format('dd/mm/yyyy');
    }

    protected $connection = 'sqlSrvMembership';
    protected $table = 'Consumer Masterdatabase Table';

    protected $casts = [
        'Celphone' => 'integer',
        'Remarks' => 'string',
        'created_at' => 'datetime:Y-m-d H:i:s.v',
        'updated_at' => 'datetime:Y-m-d H:i:s.v',
        'deleted_at' => 'datetime:Y-m-d H:i:s.v',
    ];
    // public $timestamps = false;
    protected $fillable = ['OR No', 
                        'OR No Issued',
                        'Pre-mem',
                        'First Name',
                        'Middle Name',
                        'Last Name',
                        'DateBirth',
                        'Premises',
                        'Sitio',
                        'Brgy',
                        'Municipality',
                        'District',
                        'Joint',
                        'Joint Name',
                        'MemType',
                        'Celphone',
                        'Status',
                        'Remarks',
                        'LogName',
                        'SCDate',
                        'SCExc',
                        'WaiveFirstname',
                        'WaiveLastname'
                    ];

}
