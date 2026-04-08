<?php

/**
 * FileUploadService
 *
 * Handles secure file storage for reference file attachments.
 */

namespace App\Services;

class FileUpload
{
    private string $uploadDir;

    public function __construct()
    {
        // Get the project root (two levels up from app/Services/)
        $projectRoot = dirname(__DIR__, 2);

        // Get upload directory from .env or use default 'storage/uploads'
        $uploadDirPath = env('UPLOAD_DIR', 'storage/uploads');

        // Construct full path to uploads directory
        $this->uploadDir = $projectRoot . '/' . $uploadDirPath . '/';

        // Create uploads directory if it doesn't exist (with 0755 permissions)
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function storeFile(array $file): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Generate a unique filename using UUID
        $uniqueName = bin2hex(random_bytes(16)) . '_' . basename($file['name']);
        $destination = $this->uploadDir . $uniqueName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $uniqueName; // Return the unique filename for database storage
        }

        return null;
    }
}
