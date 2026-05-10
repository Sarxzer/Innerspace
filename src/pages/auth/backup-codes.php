<?php
/**
 * @var string $includesDir
 * @var string $cssDir
 * @var string $jsDir
 */
$codes = $_SESSION['show_backup_codes'] ?? null;
if (!$codes) { header("Location: /dashboard"); exit; }
unset($_SESSION['show_backup_codes']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup Codes | Innerspace</title>
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= $cssDir ?>">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">
    <script src="/assets/js/main.js?v=<?= $jsDir?>" defer></script>
</head>
<body>
    <div class="page">
        <div class="pixel-scanlines"></div>
        <div class="content">
            <?php include $includesDir . '/navbar.php'; ?>
            <div class="main">
                <h1>Backup codes</h1>
                <p><strong>Save these somewhere safe.</strong> Each one works once. You won't see them again.</p>
                <ul class="backup-codes">
                    <?php foreach ($codes as $code): ?>
                        <li><code><?= htmlspecialchars($code) ?></code></li>
                    <?php endforeach; ?>
                </ul>
                <a href="/dashboard">I've saved them, take me to my dashboard →</a>
            </div>

            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
</body>
</html>