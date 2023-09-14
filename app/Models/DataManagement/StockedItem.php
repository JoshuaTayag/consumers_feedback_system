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

    public function temp_structure()
    {
        return $this->belongsTo('App\Models\Temporary\TemporaryStructureItem');
    }

    public function mrf_item()
    {
        return $this->belongsTo('App\Models\MaterialRequisitionFormItems');
    }

    protected $connection = 'mysqlCmbis';
    protected $table = 'item';
    protected $primaryKey = 'ItemId';
}
