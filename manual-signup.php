<?php
// Manual signup script for testing purposes. Not meant to be used in production.

require_once __DIR__ . '/../src/php/database.php';

$username = readline("Enter username: ");
$password = readline("Enter password: ");

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$database = new Database();
$pdo = $database->getPdo();
$stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->execute([$username, $password_hash]);
echo "User '$username' created with ID " . $pdo->lastInsertId() . "\n";