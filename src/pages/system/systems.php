<?php
/** @var array $parts
 * @var PDO $pdo
 * @var string $includesDir
*/

$stmt = $pdo->prepare("SELECT * FROM systems");
$stmt->execute();
$systems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// echo "<h1>Public Systems</h1>";
// if (count($systems) === 0) {
//     echo "<p>No public systems found.</p>";
// } else {
//     echo "<ul>";
//     foreach ($systems as $system) {
//         if (!$system['is_public']) {
//             continue; // Skip non-public systems
//         }
//         echo "<li><a href='/system/" . htmlspecialchars($system['handle']) . "'>" . htmlspecialchars($system['name']) . "</a> (@" . htmlspecialchars($system['handle']) . ")</li>";
//     }
//     echo "</ul>";
// }

// echo "<h2>Private Systems</h2>";
// echo "<ul>";
// foreach ($systems as $system) {
//     if ($system['is_public']) {
//         continue; // Skip public systems
//     }
//     echo "<li><a href='/system/" . htmlspecialchars($system['handle']) . "'>" . htmlspecialchars($system['name']) . "</a> (@" . htmlspecialchars($system['handle']) . ")</li>";
// }
// echo "</ul>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Systems | Innerspace</title>
</head>
<body>
    
    <?php include $includesDir . '/navbar.php'; ?>

    <h1>Systems</h1>
    <?php if (count($systems) === 0): ?>
        <p>No systems found.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($systems as $system): ?>
                <li><a href='/system/<?= htmlspecialchars($system['handle']) ?>'><?= htmlspecialchars($system['name']) ?></a> (@<?= htmlspecialchars($system['handle']) ?>) - <?= $system['is_public'] ? 'Public' : 'Private' ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>