<?php

namespace App\Models\DataManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Area extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $dates = ['created_at','updated_at','deleted_at'];

    public function feeders()
    {
        return $this->hasMany(Feeder::class);
    }

    protected $fillable = [
        'name',
    ];
}
