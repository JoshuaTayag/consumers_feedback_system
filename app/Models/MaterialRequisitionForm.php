<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequisitionForm extends Model
{
    use HasFactory;

    protected $fillable = [ 'project_name', 
                            'district_id', 
                            'municipality_id', 
                            'barangay_id', 
                            'sitio', 
                            'remarks',
                            'status',
                            'requested_id',
                            'requested_by',
                            'approved_id',
                            'approved_by',
                            'processed_id',
                            'processed_by',
                            'released_id',
                            'released_by',
                            'liquidated_id',
                            'liquidated_by',
                          ];
}
