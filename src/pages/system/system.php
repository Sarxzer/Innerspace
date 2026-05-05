<?php
/** @var array $parts
 * @var PDO $pdo
 * @var string $includesDir
*/

$parts ??= explode('/', trim($_SERVER['REQUEST_URI'], '/'));

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

$stmt = $pdo->prepare("SELECT * FROM fronting_sessions WHERE system_id = ? AND ended_at IS NULL");
$stmt->execute([$system['id']]);
$active_sessions = $stmt->fetch(PDO::FETCH_ASSOC); 

$fronting_session_members = [];
if ($active_sessions) {
    $stmt = $pdo->prepare("
        SELECT m.id, m.name
        FROM fronting_session_members fsm
        JOIN members m ON m.id = fsm.member_id
        WHERE fsm.session_id = ?
    ");
    $stmt->execute([$active_sessions['id']]);
    $fronting_session_members = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$nowFrontingMembers = [];
foreach ($fronting_session_members as $fsm) {
    if (!in_array($fsm['name'], $nowFrontingMembers, true)) {
        $nowFrontingMembers[] = $fsm['name'];
    }

}

// echo "<h1>" . htmlspecialchars($system['name']) . "</h1>";
// echo "<p>Handle: @" . htmlspecialchars($system['handle']) . "</p>";
// echo "<p>Owner: " . htmlspecialchars($user['username']) . "</p>";
// echo "<p>Description: " . nl2br(htmlspecialchars($system['description'])) . "</p>";
// echo "<p>Number of members: " . count($members) . "</p>";

// echo "Now fronting: " . (count($fronting_session_members) > 0 ? implode(", ", array_map(function($fsm) use ($pdo) {
//     $stmt = $pdo->prepare("SELECT name FROM members WHERE id = ?");
//     $stmt->execute([$fsm['member_id']]);
//     $member = $stmt->fetch(PDO::FETCH_ASSOC);
//     return htmlspecialchars($member['name']);
// }, $fronting_session_members)) : "No one") . "<br>";

// echo "<h2>Members:</h2>";
// echo "<ul>";
// foreach ($members as $member) {
//     echo "<li><a href='/system/" . htmlspecialchars($system['handle']) . "/" . htmlspecialchars($member['handle']) . "'>" . htmlspecialchars($member['name']) . "</a> (" . htmlspecialchars($member['pronouns']) . ", <span style='color: " . htmlspecialchars($member['color']) . "'>" . htmlspecialchars($member['color']) . "</span>)</li>";
// }
// echo "</ul>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($system['name']) ?> | Innerspace</title>
</head>
<body>
    <?php include $includesDir . '/navbar.php'; ?>

    <h1><?= htmlspecialchars($system['name']) ?></h1>
    <p>Handle: @<?= htmlspecialchars($system['handle']) ?></p>
    <p>Owner: <?= htmlspecialchars($user['username']) ?></p>
    <p>Description: <?= nl2br(htmlspecialchars($system['description'])) ?></p>
    <p>Number of members: <?= count($members) ?></p>

    <p>Now fronting: <?= $nowFrontingMembers ? implode(", ", $nowFrontingMembers) : "No one" ?></p>

    <h2>Members:</h2>
    <ul>
        <?php foreach ($members as $member): ?>
            <li><a href='/system/<?= htmlspecialchars($system['handle']) ?>/<?= htmlspecialchars($member['handle']) ?>'><?= htmlspecialchars($member['name']) ?></a> (<?= htmlspecialchars($member['pronouns']) ?>, <span style='color: <?= htmlspecialchars($member['color']) ?>'><?= htmlspecialchars($member['color']) ?></span>)</li>
        <?php endforeach; ?>
    </ul>
</body>
</html>