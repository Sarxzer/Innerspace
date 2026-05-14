<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../php/database.php';
require __DIR__ . '/../php/utils.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$database = new Database();
$pdo = $database->getPdo();

$active = new ActiveVisitors($pdo);
$active->cleanup();