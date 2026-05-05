<?php
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

match($parts[0]) {
    '','home' => require $pagesDir . '/home.php',
    'login'   => require $pagesDir . '/login.php',
    'dashboard'   => require $pagesDir . '/dashboard.php',
    'system'     => match(true) {
        isset($parts[2]) && !isset($parts[3]) => require $pagesDir . '/member.php',  // /system/system_name/member_name
        isset($parts[1]) && !isset($parts[2]) => require $pagesDir . '/system.php',       // /system/system_name
        default                                   => require $pagesDir . '/404.php',
    },
    'systems'    => require $pagesDir . '/systems.php',
    'fronting'    => require $pagesDir . '/fronting.php',
    'history'     => require $pagesDir . '/history.php',
    'friends'     => match(true) {
        isset($parts[1]) && $parts[1] === 'invite' => require $pagesDir . '/invite.php',     // /friends/invite
        default                                     => require $pagesDir . '/friends.php',   // /friends
    },
    'friend'      => require $pagesDir . '/friend-view.php',  // /friend/abc123token
    'settings'    => require $pagesDir . '/settings.php',
    default       => (function () use ($pagesDir) {
        http_response_code(404);
        return require $pagesDir . '/404.php';
    })(),
};