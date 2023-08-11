<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;
    
    protected $connection = 'sqlSrvMembership';
    protected $table = 'barangays';

    public function municipality()
    {
        return $this->belongsTo('App\Models\Datamanagement\Municipality');
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
