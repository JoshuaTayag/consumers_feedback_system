<?php

namespace App\Models\DataManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Structure extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function structure_items()
    {
        return $this->hasMany('App\Models\Datamanagement\StructureItem');
    }

    protected $fillable = [ 'structure_code', 
                            'remarks',
                            'status'
                          ];
}
