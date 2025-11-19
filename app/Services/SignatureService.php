<?php

namespace App\Services;

use App\Models\ChangeMeterSignature;
use App\Models\ChangeMeterRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;

class SignatureService
{
    /**
     * Store consumer signature with coordinates
     */
    public function storeConsumerSignature($changeMeterRequestId, $signatureData, $consumerName, $latitude = null, $longitude = null, $metadata = [])
    {
        DB::beginTransaction();
        try {
            // Validate base64 image
            if (!$this->isValidBase64Image($signatureData)) {
                throw new Exception('Invalid signature data format');
            }

            // Check if change meter request exists and belongs to contractor
            $changeMeterRequest = ChangeMeterRequest::findOrFail($changeMeterRequestId);
            
            // Verify contractor authorization
            if ($changeMeterRequest->crew != auth()->user()->change_meter_contractor->id) {
                throw new Exception('You are not authorized to collect signatures for this request');
            }

            // Check if customer signature already exists
            $existingSignature = ChangeMeterSignature::where('change_meter_request_id', $changeMeterRequestId)
                ->where('signature_type', 'customer')
                ->first();

            if ($existingSignature) {
                throw new Exception('Consumer signature already exists for this request');
            }

            // Create signature record
            $signature = ChangeMeterSignature::create([
                'change_meter_request_id' => $changeMeterRequestId,
                'signature_type' => 'customer',
                'signature_data' => $signatureData,
                'signatory_name' => $consumerName,
                'signatory_position' => $metadata['position'] ?? 'Consumer',
                'latitude' => $latitude,
                'longitude' => $longitude,
                'signed_at' => now(),
                'created_by' => auth()->id(),
                'metadata' => array_merge($metadata, [
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'timestamp' => now()->toISOString(),
                    'contractor_id' => auth()->user()->change_meter_contractor->id,
                    'contractor_name' => auth()->user()->change_meter_contractor->first_name . ' ' . auth()->user()->change_meter_contractor->last_name,
                    'device_info' => $metadata['device_info'] ?? null,
                ])
            ]);

            // Optional: Save image file for backup
            $this->saveSignatureImage($signature);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Consumer signature saved successfully',
                'data' => $signature
            ];

        } catch (Exception $e) {
            DB::rollback();
            
            return [
                'success' => false,
                'message' => 'Failed to save consumer signature: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get signatures for a change meter request
     */
    public function getSignatures($changeMeterRequestId)
    {
        try {
            $signatures = ChangeMeterSignature::where('change_meter_request_id', $changeMeterRequestId)
                ->with('creator:id,name')
                ->select('id', 'signature_type', 'signatory_name', 'signatory_position', 'signature_data', 'created_by', 'created_at', 'latitude', 'longitude')
                ->orderBy('created_at', 'desc')
                ->get();

            return [
                'success' => true,
                'data' => $signatures
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch signatures: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check if consumer signature exists
     */
    public function hasConsumerSignature($changeMeterRequestId)
    {
        return ChangeMeterSignature::where('change_meter_request_id', $changeMeterRequestId)
            ->where('signature_type', 'customer')
            ->exists();
    }

    /**
     * Validate base64 image format
     */
    private function isValidBase64Image($base64String)
    {
        // Remove data:image/png;base64, prefix if present
        $base64String = preg_replace('/^data:image\/[a-z]+;base64,/', '', $base64String);
        
        // Check if valid base64
        $decoded = base64_decode($base64String, true);
        if (!$decoded) {
            return false;
        }

        // Check file size (max 2MB)
        if (strlen($decoded) > 2 * 1024 * 1024) {
            return false;
        }

        // Verify it's an image
        try {
            $image = imagecreatefromstring($decoded);
            if ($image === false) {
                return false;
            }
            imagedestroy($image);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Save signature as physical file for backup
     */
    private function saveSignatureImage($signature)
    {
        try {
            $base64String = preg_replace('/^data:image\/[a-z]+;base64,/', '', $signature->signature_data);
            $imageData = base64_decode($base64String);
            
            // Create the directory structure if it doesn't exist
            $directory = public_path('images/signatures/cm_requests');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Generate filename with request ID and timestamp
            $timestamp = $signature->signed_at ? $signature->signed_at->format('Y-m-d_H-i-s') : $signature->created_at->format('Y-m-d_H-i-s');
            $filename = "consumer_{$signature->change_meter_request_id}_{$signature->id}_{$timestamp}.png";
            $fullPath = $directory . DIRECTORY_SEPARATOR . $filename;
            
            // Save the image file
            $bytesWritten = file_put_contents($fullPath, $imageData);
            
            if ($bytesWritten === false) {
                throw new Exception('Failed to write signature image file');
            }
            
            // Verify the file was created successfully
            if (!file_exists($fullPath)) {
                throw new Exception('Signature image file was not created');
            }
            
            // Return the relative path from public folder
            $relativePath = "images/signatures/cm_requests/{$filename}";
            
            \Log::info("Signature image saved successfully: {$relativePath}");
            
            return $relativePath;
        } catch (Exception $e) {
            \Log::error('Failed to save signature image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the public URL for a signature image
     */
    public function getSignatureImageUrl($signature)
    {
        try {
            // Check if signature has stored image path in metadata or generate expected path
            $timestamp = $signature->signed_at ? $signature->signed_at->format('Y-m-d_H-i-s') : $signature->created_at->format('Y-m-d_H-i-s');
            $filename = "consumer_{$signature->change_meter_request_id}_{$signature->id}_{$timestamp}.png";
            $imagePath = public_path("images/signatures/cm_requests/{$filename}");
            
            // Check if physical file exists
            if (file_exists($imagePath)) {
                return asset("images/signatures/cm_requests/{$filename}");
            }
            
            // If no physical file, return null (will use base64 from database)
            return null;
            
        } catch (Exception $e) {
            \Log::error('Failed to get signature image URL: ' . $e->getMessage());
            return null;
        }
    }
}