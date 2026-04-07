<?php 
// Load environment variables from .env file
function env($key, $default = null) {
    static $env;
    if (!$env) {
        $env = parse_ini_file(__DIR__ . '/../.env');
    }
    return $env[$key] ?? $default;

}