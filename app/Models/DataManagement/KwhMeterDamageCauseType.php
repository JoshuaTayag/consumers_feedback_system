<?php

namespace App\Models\DataManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class KwhMeterDamageCauseType extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function changeMeters()
    {
        return $this->hasMany('App\Models\ChangeMeterRequest');
    }


    protected $fillable = [
        'name',
        'description',
        'status',
    ];
}
