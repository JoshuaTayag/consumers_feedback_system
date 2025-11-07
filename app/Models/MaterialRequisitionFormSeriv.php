<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequisitionFormSeriv extends Model
{
    use HasFactory;

    public function liquidation_material()
    {
        return $this->hasOne('App\Models\MaterialRequisitionFormLiquidation', 'id', 'seriv_number')
                    ->where('type', 'SERIV');
    }

    protected $connection = 'pgsql';
    protected $table = 'seriv';
    protected $primaryKey = 'seriv_number';
    protected $keyType = 'string';  // Tell Laravel the primary key is a string (UUID)
    public $incrementing = false;  
}
