<?php
session_start();
$pagesDir = __DIR__ . '/../src/pages';
if (!is_dir($pagesDir)) {
    die("Pages directory not found: $pagesDir");
}

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts = explode('/', $uri);

// Auth check — everything except the public routes requires login
$public_routes = ['', 'login', 'friend', 'home']; // add 'home' to public routes for testing
// if (!isset($_SESSION['user_id']) && !in_array($parts[0], $public_routes)) {
//     header('Location: /login');
//     exit;
// }

// Handle pages that require headers before output
match($parts[0]) {
    'logout'  => require $pagesDir . '/auth/logout.php',
    default => null
};

if (isset($_SESSION['user_id'])) {
    // Fetch user info for use in pages
    require_once __DIR__ . '/../src/php/database.php';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $current_user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<p>Logged in as " . htmlspecialchars($current_user['username']) . " | <a href='/settings'>Settings</a> | <a href='/logout'>Logout</a></p>";
}

match($parts[0]) {
    '','home' => require $pagesDir . '/admin-test.php',
    'login'   => require $pagesDir . '/auth/login.php',
    'logout'  => null, // Already handled above 
    'dashboard'   => require $pagesDir . '/dashboard.php',
    'system'     => match(true) {
        isset($parts[2]) && !isset($parts[3]) => require $pagesDir . '/system/member.php',  // /system/system_name/member_name
        isset($parts[1]) && !isset($parts[2]) => require $pagesDir . '/system/system.php',       // /system/system_name
        default                                   => require $pagesDir . '/error/404.php',
    },
    'systems'    => require $pagesDir . '/system/systems.php',
    'fronting'    => require $pagesDir . '/dashboard/fronting.php',
    'history'     => require $pagesDir . '/dashboard/history.php',
    'friends'     => match(true) {
        isset($parts[1]) && $parts[1] === 'invite' => require $pagesDir . '/invite.php',     // /friends/invite
        default                                     => require $pagesDir . '/friends.php',   // /friends
    },
    'friend'      => require $pagesDir . '/friend-view.php',  // /friend/abc123token
    'settings'    => require $pagesDir . '/settings/settings.php',
    default       => (function () use ($pagesDir) {
        http_response_code(404);
        return require $pagesDir . '/404.php';
    })(),
};