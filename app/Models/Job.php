<?php

namespace App\Models;

Class Job
{
    public function __construct(
        public string $title,
        public ?string $script,
        public string $country,
        public string $state_or_province,
        public ?string $reference_file_path,
        public float $budget,
        public string $ip_address
    ) {
    }
}