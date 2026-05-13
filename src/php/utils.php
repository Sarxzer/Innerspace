<?php

/**
 * Handle CSRF token generation and verification.
 */
class Csrf {

    /**
     * Generate a CSRF token and store it in the session if it doesn't already exist.
     * @return void
     */
    public static function generate(): void {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function verify(): void {
        if (
            empty($_POST['csrf_token']) ||
            !isset($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            http_response_code(403);
            Alert::error('Invalid CSRF token. Please try again.');
            Alert::dev('CSRF token verification failed. Expected: ' . ($_SESSION['csrf_token'] ?? 'null') . ', Received: ' . ($_POST['csrf_token'] ?? 'null'));
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }
    }

    public static function token(): string {
        return $_SESSION['csrf_token'] ?? '';
    }
}




function loadFile(string $path): string {
    if (!file_exists($path)) {
        
    }
    return file_get_contents($path);
}