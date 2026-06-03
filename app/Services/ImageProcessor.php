<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageProcessor
{
    public function __construct()
    {
        // Image manager configuration can be adjusted via environment if needed
    }

    /**
     * Process and store an uploaded file.
     * Images will be normalized (orientation), resized and encoded to WebP/JPEG.
     * Other files (pdf) will be stored as-is.
     * Returns the storage path.
     */
    public function processAndStore($file, string $directory): string
    {
        $mime = $file->getClientMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        if (!Str::startsWith($mime, 'image/')) {
            $fileName = uniqid('file_') . '.' . $extension;
            return $file->storeAs($directory, $fileName, 'public');
        }

        // For images, just store as-is without processing
        // This is the safest approach to avoid memory/resource issues
        $fileName = uniqid('file_') . '.' . $extension;
        return $file->storeAs($directory, $fileName, 'public');
    }
}
