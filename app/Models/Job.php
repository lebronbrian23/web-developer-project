<?php
/**
 * Job
 *
 * Represents a job entry in the system.
 */

namespace App\Models;

class Job
{
    public function __construct(
        public string $title,
        public ?string $script,
        public string $country,
        public string $state_or_province,
        public ?string $reference_file_path,
        public string $budget,
        public string $ip_address
    ) {
    }
}
