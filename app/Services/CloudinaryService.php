<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key'    => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
        ]);
    }

    /**
     * ✅ Upload PDF to Cloudinary and get public URL
     * 
     * @param string $filePath Local file path
     * @param string $fileName Original filename
     * @param int $expiresInHours URL expiration time (default 24 hours)
     * @return array|null ['url' => 'https://...', 'public_id' => '...']
     */
    public function uploadReceipt($filePath, $fileName, $expiresInHours = 24)
{
    try {
        if (!file_exists($filePath)) {
            throw new \Exception('File not found: ' . $filePath);
        }

        $fileSize = filesize($filePath);
        
        Log::info('📤 Uploading PDF to Cloudinary', [
            'filename' => $fileName,
            'size_kb' => round($fileSize / 1024, 2),
            'path' => $filePath
        ]);

        $publicId = 'receipts/' . pathinfo($fileName, PATHINFO_FILENAME);

        // ✅ Upload sebagai PUBLIC (karena PDF delivery sudah enabled)
        $result = $this->cloudinary->uploadApi()->upload($filePath, [
            'public_id' => $publicId,
            'folder' => 'skyramart/receipts',
            'resource_type' => 'raw',
            'type' => 'upload', // Public upload
            'access_mode' => 'public', // ✅ PENTING
            'overwrite' => true,
            'invalidate' => true,
        ]);

        $secureUrl = $result['secure_url'];

        Log::info('✅ PDF uploaded to Cloudinary', [
            'url' => $secureUrl,
            'public_id' => $result['public_id'],
            'bytes' => $result['bytes']
        ]);

        return [
            'url' => $secureUrl,
            'public_id' => $result['public_id'],
            'expires_at' => now()->addHours($expiresInHours)
        ];

    } catch (\Exception $e) {
        Log::error('❌ Cloudinary upload failed', [
            'error' => $e->getMessage(),
            'filename' => $fileName
        ]);
        return null;
    }
}

    /**
     * ✅ Delete receipt from Cloudinary (optional cleanup)
     */
    public function deleteReceipt($publicId)
    {
        try {
            $result = $this->cloudinary->uploadApi()->destroy($publicId, [
                'resource_type' => 'raw'
            ]);

            Log::info('🗑️ PDF deleted from Cloudinary', [
                'public_id' => $publicId,
                'result' => $result['result']
            ]);

            return $result['result'] === 'ok';

        } catch (\Exception $e) {
            Log::error('Failed to delete from Cloudinary', [
                'error' => $e->getMessage(),
                'public_id' => $publicId
            ]);
            return false;
        }
    }

    /**
     * ✅ Generate time-limited signed URL (more secure)
     * Use this if you want URLs that expire automatically
     */
    public function getSignedUrl($publicId, $expiresInSeconds = 86400)
    {
        try {
            $timestamp = time() + $expiresInSeconds;
            
            $url = $this->cloudinary->image($publicId)
                ->delivery("fl_attachment")
                ->addTransformation("t_{$timestamp}")
                ->toUrl();

            return $url;

        } catch (\Exception $e) {
            Log::error('Failed to generate signed URL', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}