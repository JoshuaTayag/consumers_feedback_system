<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $connection = 'sqlSrvMembership';
    protected $table = 'municipalities';

    public function barangay()
    {
        return $this->hasMany('App\Models\Barangay');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District');
    }

    public function preMembership()
    {
        return $this->hasMany('App\Models\Premembership');
    }

    public function lifeline()
    {
        return $this->hasMany('App\Models\Lifeline');
    }
}
