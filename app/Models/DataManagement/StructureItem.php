<?php

namespace App\Models\DataManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class StructureItem extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function getAllItemsAttribute($value)
    {
        return $this->items[0]->Description;
    }

    public function structure()
    {
        return $this->belongsTo('App\Models\Datamanagement\Structure', 'structure_id', 'id');
    }

    public function item()
    {
        return $this->hasOne('App\Models\Datamanagement\StockedItem', 'ItemId', 'item_id');
    }
    
    protected $fillable = [ 'structure_id', 
                            'item_id',
                            'unit_cost',
                            'quantity'
                          ];
}
