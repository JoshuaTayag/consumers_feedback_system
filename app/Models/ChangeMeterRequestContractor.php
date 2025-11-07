<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeMeterRequestContractor extends Model
{
    use HasFactory;

    public function change_meter_request()
    {
        return $this->belongsTo('App\Models\ChangeMeterRequest');
    }

    protected $table = 'change_meter_contractors';
}
