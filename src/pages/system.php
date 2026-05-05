<?php
/** @var array $parts */
$parts ??= explode('/', trim($_SERVER['REQUEST_URI'], '/'));

require_once __DIR__ . '/../php/database.php';

$system_handle = $parts[1];

$stmt = $pdo->prepare("SELECT * FROM systems WHERE handle = ?");
$stmt->execute([$system_handle]);
$system = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$system) {
    die("System not found.");
}

$stmt = $pdo->prepare("SELECT * FROM members WHERE system_id = ?");
$stmt->execute([$system['id']]);
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$system['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h1>" . htmlspecialchars($system['name']) . "</h1>";
echo "<p>Handle: @" . htmlspecialchars($system['handle']) . "</p>";
echo "<p>Owner: " . htmlspecialchars($user['username']) . "</p>";
echo "<p>Description: " . nl2br(htmlspecialchars($system['description'])) . "</p>";
echo "<p>Number of members: " . count($members) . "</p>";
echo "<h2>Members:</h2>";
echo "<ul>";
foreach ($members as $member) {
    echo "<li><a href='/system/" . htmlspecialchars($system['handle']) . "/" . htmlspecialchars($member['handle']) . "'>" . htmlspecialchars($member['name']) . "</a> (" . htmlspecialchars($member['pronouns']) . ", <span style='color: " . htmlspecialchars($member['color']) . "'>" . htmlspecialchars($member['color']) . "</span>)</li>";
}
echo "</ul>";