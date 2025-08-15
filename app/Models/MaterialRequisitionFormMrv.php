<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequisitionFormMrv extends Model
{
    use HasFactory;

    public function liquidation_material()
    {
        return $this->hasOne('App\Models\MaterialRequisitionFormLiquidation', 'id', 'mrv_number')
                    ->where('type', 'MRV');
    }

    protected $connection = 'pgsql';
    protected $table = 'mrv';
    protected $primaryKey = 'mrv_number';
    protected $keyType = 'string';  // Tell Laravel the primary key is a string (UUID)
    public $incrementing = false;  
}
