<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pending extends Model
{
    use HasFactory;

    public function senderUser()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function recipientUser()
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }

    // Generic relationship - gets the related model based on table_name
    public function relatedModel()
    {
        switch($this->table_name) {
            case 'kwh_meter_requests':
                return $this->belongsTo(KwhMeterRequest::class, 'table_id');
            case 'change_meter_requests':
                return $this->belongsTo(ChangeMeterRequest::class, 'table_id');
            // Add other model relationships as needed
            default:
                return null;
        }
    }

    // Specific relationship for KWH Meter Requests
    public function kwhMeterRequest()
    {
        return $this->belongsTo(KwhMeterRequest::class, 'table_id');
    }

    // Accessor to get the actual related model instance
    public function getRelatedRecordAttribute()
    {
        switch($this->table_name) {
            case 'kwh_meter_requests':
                return $this->kwhMeterRequest;
            case 'change_meter_requests':
                return $this->changeMeterRequest ?? null;
            default:
                return null;
        }
    }


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
        'updated_at',
        'remarks'
    ];

    protected $table = 'pending';
}
