<?php

/**
 * Validator
 *
 * Handles validation of various input fields and file uploads.
 */


namespace App\Services;

class Validator
{
    private array $errors = [];

    private int $maxFileSize;
    
    private static ?array $provinces = null;

    public function __construct()
    {
        $this->maxFileSize = (int) env('MAX_FILE_SIZE', 20 * 1024 * 1024); // 20MB default
    }

    // Main validation method
    public function validate(array $data, array $files = []): bool
    {
        $this->errors = [];

        // Normalize input
        $data = array_map(fn($value) => is_string($value) ? trim($value) : $value, $data);

        $this->validateTitle($data['title'] ?? '');
        $this->validateScript($data['script'] ?? '');
        $this->validateCountry($data['country'] ?? '');
        $this->validateStateOrProvince($data['state_or_province'] ?? '', $data['country'] ?? '');
        $this->validateBudget($data['budget'] ?? '');

        if (!empty($files['reference_file_path']['name'])) {
            $this->validateFileUpload($files['reference_file_path']);
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    // -------------------------
    // Validation Methods
    // -------------------------

    // validate title as a required field and must not exceed 255 characters
    private function validateTitle(string $value): void
    {
        if ($value === '') {
            $this->errors['title'] = 'Job title is required.';
            return;
        }

        if (strlen($value) > 255) {
            $this->errors['title'] = 'Job title must not exceed 255 characters.';
        }
    }

    // validate script as an optional field but if provided must not exceed 1000 words
    private function validateScript(string $value): void
    {
        if ($value === '') {
            return;
        }

        // Match JavaScript word counting: split by whitespace
        // This counts numbers and all tokens separated by whitespace
        $trimmed = trim($value);
        $wordCount = $trimmed === '' ? 0 : count(array_filter(explode(' ', preg_replace('/\s+/', ' ', $trimmed))));

        if ($wordCount > 1000) {
            $this->errors['script'] = 'Job script must not exceed 1000 words.';
        }
    }

    // validate country as a required field and must be one of the allowed values
    private function validateCountry(string $value): void
    {
        if ($value === '') {
            $this->errors['country'] = 'Country is required.';
            return;
        }

        if (!array_key_exists($value, self::getProvinces())) {
            $this->errors['country'] = 'Please select a valid country.';
        }
    }

    // validate state_or_province as a required field and must be valid for the selected country
    private function validateStateOrProvince(string $value, string $country): void
    {
        if ($value === '') {
            $this->errors['state_or_province'] = 'State/Province is required.';
            return;
        }

        $provinces = self::getProvinces();
        if (
            isset($provinces[$country]) &&
            !array_key_exists($value, $provinces[$country])
        ) {
            $this->errors['state_or_province'] = 'Please select a valid state/province.';
        }
    }

    // validate budget as a required field and must be one of the allowed values
    private function validateBudget(string $value): void
    {
        $allowed = ['low', 'medium', 'high'];

        if ($value === '') {
            $this->errors['budget'] = 'Budget is required.';
            return;
        }

        if (!in_array($value, $allowed, true)) {
            $this->errors['budget'] = 'Invalid budget selection.';
        }
    }

    // validate file upload for reference_file_path as an optional field but if provided must be a valid file type and within size limits
    private function validateFileUpload(array $file): void
    {
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors['reference_file_path'] = match ($file['error']) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the maximum allowed size.',
                UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
                default => 'File upload failed. Please try again.',
            };
            return;
        }

        // File size validation
        if ($file['size'] > $this->maxFileSize) {
            $this->errors['reference_file_path'] =
                'The uploaded file exceeds the maximum allowed size of ' .
                ($this->maxFileSize / (1024 * 1024)) . ' MB.';
            return;
        }

        // Secure MIME type check
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            $this->errors['reference_file_path'] = 'Invalid file type.';
        }
    }

    // Load provinces data from a separate file 
    public static function getProvinces(): array
    {
        if (self::$provinces === null) {
            self::$provinces = require __DIR__ . '/../Data/Provinces.php';
        }
        return self::$provinces;
    }

    // -------------------------
    // Constants
    // -------------------------

    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'text/plain',
        'audio/mpeg',
        'audio/wav',
        'audio/mp4',
        'video/mp4',
    ];
}
