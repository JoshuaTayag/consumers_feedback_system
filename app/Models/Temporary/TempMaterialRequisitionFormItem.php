<?php

namespace App\Models\Temporary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempMaterialRequisitionFormItem extends Model
{
    use HasFactory;

    public function item()
    {
        return $this->hasOne('App\Models\Datamanagement\StockedItem', 'id', 'item_id');
    }
    
}
