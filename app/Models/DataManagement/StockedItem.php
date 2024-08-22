<?php

namespace App\Models\DataManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

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

    public function mcrt_item()
    {
        return $this->belongsTo('App\Models\MaterialRequisitionFormMcrtDetails');
    }

    public function mst_item()
    {
        return $this->belongsTo('App\Models\MaterialRequisitionFormMstDetails');
    }

    public function getUnitNameAttribute()
    {

        // $user = User::where('id', )
        //     ->get();
        $items = DB::connection('mysqlCmbis')
        ->table('unit')
        ->where('UnitId', $this->UnitId)
        ->select('UnitName')->get();
        return $items[0]->UnitName;
    }

    protected $connection = 'mysqlCmbis';
    protected $table = 'item';
    protected $primaryKey = 'ItemId';
}
