<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pending extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction',
        'table_name',
        'url',
        'table_id',
        'sender_user_id',
        'recipient_user_id',
        'approval_step',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $table = 'pending';
}
