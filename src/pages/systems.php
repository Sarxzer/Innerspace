<?php

require_once __DIR__ . '/../php/database.php';

$stmt = $pdo->prepare("SELECT * FROM systems");
$stmt->execute();
$systems = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>Public Systems</h1>";
if (count($systems) === 0) {
    echo "<p>No public systems found.</p>";
} else {
    echo "<ul>";
    foreach ($systems as $system) {
        if (!$system['is_public']) {
            continue; // Skip non-public systems
        }
        echo "<li><a href='/system/" . htmlspecialchars($system['handle']) . "'>" . htmlspecialchars($system['name']) . "</a> (@" . htmlspecialchars($system['handle']) . ")</li>";
    }
    echo "</ul>";
}

echo "<h2>Private Systems</h2>";
echo "<ul>";
foreach ($systems as $system) {
    if ($system['is_public']) {
        continue; // Skip public systems
    }
    echo "<li><a href='/system/" . htmlspecialchars($system['handle']) . "'>" . htmlspecialchars($system['name']) . "</a> (@" . htmlspecialchars($system['handle']) . ")</li>";
}
echo "</ul>";