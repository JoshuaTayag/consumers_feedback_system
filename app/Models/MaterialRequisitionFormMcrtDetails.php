<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequisitionFormMcrtDetails extends Model
{
    use HasFactory;

    protected $table = 'MCRT Transaction Table';

    public function material_requisition_form()
    {
        return $this->belongsTo('App\Models\MaterialRequisitionForm',  'mcrt_no', 'MCRTNo');
    }

    public function stock_item()
    {
        return $this->hasOne('App\Models\Datamanagement\StockedItem', 'ItemCode', 'CodeNo');
    }
}
