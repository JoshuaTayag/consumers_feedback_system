<?php

namespace App\Models\DataManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockedItem extends Model
{
    use HasFactory;

    public function structure_item()
    {
        return $this->belongsTo('App\Models\Datamanagement\StructureItem');
    }

    public function temp_mrf()
    {
        return $this->belongsTo('App\Models\Datamanagement\StructureItem');
    }

    public function mrf()
    {
        return $this->belongsTo('App\Models\MaterialRequisitionFormItems');
    }

    public function posts()
    {
        return $this->hasManyThrough(
                                    'App\Models\MaterialRequisitionForm', 'App\Models\MaterialRequisitionFormItems',
                                    'item_id', 'material_requisition_form_id', 'id'
                                        );
    }

    protected $connection = 'mysqlCmbis';
    protected $table = 'item';
    protected $primaryKey = 'ItemId';
}
