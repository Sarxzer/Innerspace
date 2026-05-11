<?php

$auth = new Auth($pdo);
$auth->requireLogin();
$auth->logout();
header('Location: /login');

exit;