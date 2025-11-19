<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class ChangeMeterSignature extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'change_meter_request_id',
        'signature_type',
        'signature_data',
        'signatory_name',
        'signatory_position',
        'latitude',
        'longitude',
        'signed_at',
        'created_by',
        'metadata'
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'metadata' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    // Relationships
    public function changeMeterRequest()
    {
        return $this->belongsTo(ChangeMeterRequest::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessors
    public function getSignedAtFormattedAttribute()
    {
        return $this->signed_at->format('M d, Y h:i A');
    }

    public function getLocationFormattedAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return $this->latitude . ', ' . $this->longitude;
        }
        return 'No location recorded';
    }

    public function getGoogleMapsLinkAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        return null;
    }

    public function getSignatureImageUrlAttribute()
    {
        // Try to get physical image file first
        $timestamp = $this->signed_at ? $this->signed_at->format('Y-m-d_H-i-s') : $this->created_at->format('Y-m-d_H-i-s');
        $filename = "consumer_{$this->change_meter_request_id}_{$this->id}_{$timestamp}.png";
        $imagePath = public_path("images/signatures/cm_requests/{$filename}");
        
        // Check if physical file exists
        if (file_exists($imagePath)) {
            return asset("images/signatures/cm_requests/{$filename}");
        }
        
        // If no physical file, return base64 data URL
        if ($this->signature_data) {
            // Check if signature_data already contains the data URL prefix
            if (strpos($this->signature_data, 'data:image/') === 0) {
                return $this->signature_data; // Already has the full data URL
            } else {
                return "data:image/png;base64,{$this->signature_data}"; // Add the prefix
            }
        }
        
        return null;
    }
}