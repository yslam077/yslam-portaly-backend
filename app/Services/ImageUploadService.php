<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    public function upload(?UploadedFile $file, string $folder = 'uploads'): ?string
    {
        if (!$file) {
            return null;
        }

        $path = $file->store($folder, 'public');

        return $path;
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}