<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequisitionFormLiquidation extends Model
{
    use HasFactory;

    public function material_requisition_form()
    {
        return $this->belongsTo('App\Models\MaterialRequisitionForm',  'material_requisition_form_id', 'id');
    }

    public function seriv_details(){
        return $this->hasOne('App\Models\MaterialRequisitionFormSeriv', 'seriv_number', 'type_number');
    }

    public function mrv_details(){
        return $this->hasOne('App\Models\MaterialRequisitionFormMrv', 'mrv_number', 'type_number');
    }
}
