<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if ($_ENV['APP_DEBUG'] === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

require_once __DIR__ . '/../src/php/database.php';


session_start();

$pagesDir = __DIR__ . '/../src/pages';
$includesDir = __DIR__ . '/../src/includes';

if (!is_dir($pagesDir)) {
    die("Pages directory not found: $pagesDir");
}

$uri    = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts  = explode('/', $uri);

// Auth guard
$protected_routes = ['dashboard', 'manage', 'settings', 'fronting', 'history', 'friends'];
if (in_array($parts[0], $protected_routes) && !isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

// Fetch current user if logged in
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $current_user = $stmt->fetch(PDO::FETCH_ASSOC);
}


match ($parts[0]) {
    // Public
    ''  => header('Location: /home'), // Redirect root to home
    'home'  => require $pagesDir . '/home.php',
    'login'     => require $pagesDir . '/auth/login.php',
    'register'  => require $pagesDir . '/auth/register.php',
    'logout'    => require $pagesDir . '/auth/logout.php',

    // Public system/member viewing
    'systems' => header('Location: /system'), // Redirect /systems to /system for now
    'system' => match (true) {
        isset($parts[1]) && isset($parts[2]) => require $pagesDir . '/system/member.php',   // /system/{handle}/{member_handle}
        isset($parts[1])                     => require $pagesDir . '/system/system.php',   // /system/{handle}
        default                              => require $pagesDir . '/system/systems.php',       // /system alone makes no sense but we use it to list all systems in dev
    },

    // Authenticated
    'dashboard' => require $pagesDir . '/dashboard/dashboard.php',
    'fronting'  => require $pagesDir . '/dashboard/fronting.php',
    'history'   => require $pagesDir . '/dashboard/history.php',
    'settings'  => require $pagesDir . '/settings/settings.php',

    'friends' => match (true) {
        isset($parts[1]) && $parts[1] === 'invite' => require $pagesDir . '/friends/invite.php',  // /friends/invite
        default                                     => require $pagesDir . '/friends/friends.php', // /friends
    },
    'friend' => require $pagesDir . '/friends/friend-view.php', // /friend/{token}

    // System management (authenticated)
    'manage' => match (true) {
        $parts[1] === 'system' && isset($parts[2]) && $parts[3] === 'member' && isset($parts[4])
        => require $pagesDir . '/manage/member-edit.php',   // /manage/system/{handle}/member/{member_handle}
        $parts[1] === 'system' && isset($parts[2]) && $parts[3] === 'members'
        => require $pagesDir . '/manage/members.php',       // /manage/system/{handle}/members
        $parts[1] === 'system' && isset($parts[2])
        => require $pagesDir . '/manage/system-edit.php',   // /manage/system/{handle}
        default                           => require $pagesDir . '/manage/systems.php',       // /manage or /manage/systems
    },

    // Fallback
    default => (function () use ($pagesDir) {
        http_response_code(404);
        require $pagesDir . '/errors/404.php';
    })(),
};
