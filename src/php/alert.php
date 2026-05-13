<?php

/**
 * Alert/Message Handler
 * Manages flash messages and alerts that persist across redirects
 */
class Alert
{
    private const SESSION_KEY = 'alerts';

    /**
     * Add a success message
     * @param string $message The message to display
     */
    public static function success(string $message): void
    {
        self::add($message, 'success');
    }

    /**
     * Add an error message
     * @param string $message The message to display
     */
    public static function error(string $message): void
    {
        self::add($message, 'error');
    }

    /**
     * Add a warning message
     * @param string $message The message to display
     */
    public static function warning(string $message): void
    {
        self::add($message, 'warning');
    }

    /**
     * Add an info message
     * @param string $message The message to display
     */
    public static function info(string $message): void
    {
        self::add($message, 'info');
    }

    /**
     * Add a development/debug message (only shown when APP_DEBUG is true)
     * @param string $message
     * @return void
     */
    public static function dev(string $message): void
    {
        if ($_ENV['APP_DEBUG'] === 'true') {
            self::add($message, 'dev');
        }
    }

    /**
     * Internal method to add alert to session
     * @param string $message The message to display
     * @param string $type The type of alert (success, error, warning, info)
     */
    private static function add(string $message, string $type): void
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }
        $_SESSION[self::SESSION_KEY][] = [
            'message' => $message,
            'type' => $type,
        ];
    }

    /**
     * Get all alerts and clear them from session
     * @return array
     */
    public static function getAll(): array
    {
        $alerts = $_SESSION[self::SESSION_KEY] ?? [];
        unset($_SESSION[self::SESSION_KEY]);
        return $alerts;
    }

    /**
     * Check if there are any alerts
     * @return bool
     */
    public static function hasAlerts(): bool
    {
        return !empty($_SESSION[self::SESSION_KEY]);
    }
}
