<?php
/**
 * @var array $parts
 * @var PDO $pdo
 * @var string $includesDir
 * @var string $cssDir
 * @var string $jsDir

 */
$auth = new Auth($pdo);
$auth->requireLogin();
$auth->logout();

Alert::success("You have been logged out.");
header('Location: /login');
exit;