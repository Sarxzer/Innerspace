<?php
/** @var array $parts
 * @var PDO $pdo
 * @var string $includesDir
*/

$parts ??= explode('/', trim($_SERVER['REQUEST_URI'], '/'));

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

// echo "<h1>" . htmlspecialchars($member['name']) . "</h1>";
// echo "<p>System: <a href='/system/" . htmlspecialchars($system['handle']) . "'>" . htmlspecialchars($system['name']) . "</a></p>";
// echo "<p>Pronouns: " . htmlspecialchars($member['pronouns']) . "</p>";
// echo "<p>Color: <span style='color: " . htmlspecialchars($member['color']) . "'>" . htmlspecialchars($member['color']) . "</span></p>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($member['name']) ?> | Innerspace</title>
</head>
<body>
    <?php include $includesDir . '/navbar.php'; ?>

    <h1><?= htmlspecialchars($member['name']) ?></h1>
    <p>System: <a href='/system/<?= htmlspecialchars($system['handle']) ?>'><?= htmlspecialchars($system['name']) ?></a></p>
    <p>Pronouns: <?= htmlspecialchars($member['pronouns']) ?></p>
    <p>Color: <span style='color: <?= htmlspecialchars($member['color']) ?>'><?= htmlspecialchars($member['color']) ?></span></p>
</body>
</html>