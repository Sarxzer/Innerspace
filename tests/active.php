<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/php/utils.php';
require __DIR__ . '/../src/php/database.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$database = new Database();
$pdo = $database->getPdo();

$active = new ActiveVisitors($pdo);


echo $active->countOnline(); // 12
echo $active->countUsers();  // 5
echo $active->countGuests(); // 7

print_r($active->perPage());