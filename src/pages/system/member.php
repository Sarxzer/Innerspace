<?php
/** @var array $parts */
$parts ??= explode('/', trim($_SERVER['REQUEST_URI'], '/'));

require_once __DIR__ . '/../php/database.php';

$system_handle = $parts[1];
$member_handle = $parts[2];

$stmt = $pdo->prepare("SELECT * FROM systems WHERE handle = ?");
$stmt->execute([$system_handle]);
$system = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$system) {
    die("System not found.");
}

$stmt = $pdo->prepare("SELECT * FROM members WHERE system_id = ? AND handle = ?");
$stmt->execute([$system['id'], $member_handle]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {
    die("Member not found.");
}

echo "<h1>" . htmlspecialchars($member['name']) . "</h1>";
echo "<p>System: <a href='/system/" . htmlspecialchars($system['handle']) . "'>" . htmlspecialchars($system['name']) . "</a></p>";
echo "<p>Pronouns: " . htmlspecialchars($member['pronouns']) . "</p>";
echo "<p>Color: <span style='color: " . htmlspecialchars($member['color']) . "'>" . htmlspecialchars($member['color']) . "</span></p>";