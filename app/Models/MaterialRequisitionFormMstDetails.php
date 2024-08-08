<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequisitionFormMstDetails extends Model
{
    use HasFactory;

    protected $table = 'MST Transaction Table';

    public function material_requisition_form()
    {
        return $this->belongsTo('App\Models\MaterialRequisitionForm',  'mst_no', 'MSTNo');
    }
}
