<?php
/**
 * Logger
 *
 * Handles application logging for various events and errors.
 */

namespace App\Services;

class Logger
{
    private static string $logsDir = __DIR__ . '/../../storage/logs';

    public static function info(string $message, string $logFile = 'app.log'): void
    {
        self::write($message, $logFile);
    }

    public static function database(string $message, bool $success = true): void
    {
        $prefix = $success ? '✓' : '✗';
        $logMessage = "[" . date('Y-m-d H:i:s') . "] {$prefix} {$message}\n";
        self::write($logMessage, 'database.log');
    }

    public static function email(string $message, bool $sent = true): void
    {
        $prefix = $sent ? '✓' : '✗';
        $logMessage = "[" . date('Y-m-d H:i:s') . "] {$prefix} {$message}\n";
        self::write($logMessage, 'mailer.log');
    }

    public static function form(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "\n[{$timestamp}] {$message}\n";
        self::write($logMessage, 'form_submissions.log');
    }

    public static function error(string $message, ?array $context = null): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] ERROR: {$message}";

        if ($context) {
            $logMessage .= "\nContext: " . json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        $logMessage .= "\n";
        self::write($logMessage, 'error.log');
        error_log($logMessage);
    }

    public static function data(array $data, string $label = 'Data'): string
    {
        return "{$label}:\n" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public static function section(string $title): string
    {
        return "=== {$title} ===";
    }

    private static function write(string $message, string $logFile): void
    {
        self::ensureLogsDirectory();
        $filePath = self::$logsDir . '/' . $logFile;
        file_put_contents($filePath, $message, FILE_APPEND);
    }

    private static function ensureLogsDirectory(): void
    {
        if (!is_dir(self::$logsDir)) {
            mkdir(self::$logsDir, 0755, true);
        }
    }
}
