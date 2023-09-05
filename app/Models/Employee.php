<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    protected $fillable = [
        'user_id',
        'prefix',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'position',
        'signature_path',
    ];
}
