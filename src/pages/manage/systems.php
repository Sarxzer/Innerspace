<?php
/**
 * @var PDO $pdo
 * @var string $includesDir
 */

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT * FROM systems WHERE user_id = ?');
$stmt->execute([$userId]);
$systems = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($systems)) {
    header('Location: /manage/systems/new');
    exit;
}

// For now, this page will redirect to the first system's edit page, but in the future we can expand it for multiple systems per user and have a proper listing page here.
header('Location: /manage/system/' . $systems[0]['handle']);
exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage | Innerspace</title>
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= filemtime(__DIR__ . '/../../../public/assets/css/style.css') ?>">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">
    <script src="/assets/js/main.js?v=<?= filemtime(__DIR__ . '/../../public/assets/js/main.js') ?>" defer></script>
</head>

<body>
    <div class="page">
        <div class="pixel-scanlines"></div>
        <div class="content">
            <?php include $includesDir . '/navbar.php'; ?>

            <h1>Manage Systems</h1>

            <div class="system-list">
                <?php foreach ($systems as $system): ?>
                    <a href="/manage/system/<?php echo htmlspecialchars($system['handle']); ?>" class="system-card">
                        <h2><?php echo htmlspecialchars($system['name']); ?></h2>
                        <p>@<?php echo htmlspecialchars($system['handle']); ?></p>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
</body>