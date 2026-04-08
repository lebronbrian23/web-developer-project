<?php

/**
 * Global Logger Helper Functions
 * Include these functions in your app for easy logging from anywhere
 */

use App\Services\Logger;

if (!function_exists('log_info')) {
    function log_info(string $message, string $logFile = 'app.log'): void
    {
        Logger::info($message, $logFile);
    }
}

if (!function_exists('log_database')) {
    function log_database(string $message, bool $success = true, ?array $data = null): void
    {
        if ($data) {
            $message .= "\n" . Logger::data($data);
        }
        Logger::database($message, $success);
    }
}

if (!function_exists('log_email')) {
    function log_email(string $message, bool $sent = true, ?array $data = null): void
    {
        if ($data) {
            $message .= "\n" . Logger::data($data);
        }
        Logger::email($message, $sent);
    }
}

if (!function_exists('log_form')) {
    function log_form(string $message, ?array $data = null): void
    {
        if ($data) {
            $message .= "\n" . Logger::data($data);
        }
        Logger::form($message);
    }
}

if (!function_exists('log_error')) {
    function log_error(string $message, ?array $context = null): void
    {
        Logger::error($message, $context);
    }
}

if (!function_exists('section')) {
    function section(string $title): string
    {
        return Logger::section($title);
    }
}
