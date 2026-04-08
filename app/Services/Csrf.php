<?php

namespace App\Services;

class Csrf
{
    // Generate a new CSRF token and store it in the session
    public function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_tokens'];
    }

    // Verify the provided CSRF token against the one stored in the session
    public function verifyCsrfToken(string $token): bool
    {
        if (isset($_SESSION['csrf_tokens']) && hash_equals($_SESSION['csrf_tokens'], $token)) {
            unset($_SESSION['csrf_tokens']);
            return true;
        }
        return false;
    }
}
