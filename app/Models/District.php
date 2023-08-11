<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $connection = 'sqlSrvMembership';
    protected $table = 'districts';

    public function municipality()
    {
        return $this->hasMany('App\Models\Municipality');
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
