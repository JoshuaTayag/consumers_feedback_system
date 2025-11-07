<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequisitionFormMstDetails extends Model
{
    use HasFactory;

    public function material_requisition_form()
    {
        return $this->belongsTo('App\Models\MaterialRequisitionForm',  'mst_no', 'mst_id');
    }

    public function stock_item()
    {
        return $this->hasOne('App\Models\Datamanagement\StockedItem', 'id', 'item_id');
    }

    protected $connection = 'pgsql';
    protected $table = 'mst_item';
    protected $primaryKey = 'id';
    protected $keyType = 'string';  // Tell Laravel the primary key is a string (UUID)
    public $incrementing = false;  
}
