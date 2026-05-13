<?php

/**
 * @var PDO $pdo
 * @var string $includesDir
 * @var string $cssDir
 * @var string $jsDir
 * @var array $current_user
 */

$userId = $_SESSION['user_id'];

$handle = $parts[2] ?? null;

$stmt = $pdo->prepare('SELECT * FROM systems WHERE handle = ?');
$stmt->execute([$handle]);
$system = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$system['user_id'] || $system['user_id'] != $userId) {
    Alert::error("System not found or you don't have permission to manage it.");
    header('Location: /dashboard');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage | Innerspace</title>
    <link rel="stylesheet" href="<?= $cssDir ?>">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">
    <script src="<?= $jsDir ?>" defer></script>
</head>

<body>
    <div class="page">
        <div class="pixel-scanlines"></div>
        <div class="content">
            <?php include $includesDir . '/navbar.php'; ?>
            <div class="alerts-wrapper">
                <?php include $includesDir . '/alerts.php'; ?>
            </div>

            <div class="main">
                <h1>Manage <?= htmlspecialchars($system['name']) ?></h1>

                <p>Welcome to the management dashboard for the <?= htmlspecialchars($system['name']) ?> system.</p>
            </div>

            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
</body>

</html>