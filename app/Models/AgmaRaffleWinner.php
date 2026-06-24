<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgmaRaffleWinner extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'prize', 'account_no'];
}
