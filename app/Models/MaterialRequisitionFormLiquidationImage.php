<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequisitionFormLiquidationImage extends Model
{
    use HasFactory;

    public function materialRequisitionForm()
    {
        return $this->belongsTo(MaterialRequisitionForm::class, 'material_requisition_form_id', 'id');
    }   
}
