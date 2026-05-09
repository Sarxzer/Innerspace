<?php
/**
 * @var PDO $pdo
 * @var string $includesDir
 */

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT * FROM systems WHERE user_id = ?');
$stmt->execute([$userId]);
$systems = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage | Innerspace</title>
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= filemtime(__DIR__ . '/../../../public/assets/css/style.css') ?>">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">
</head>

<body>
    <div class="page">
        <div class="pixel-scanlines"></div>
        <div class="content">
            <?php include $includesDir . '/navbar.php'; ?>

            <h1>Manage Systems</h1>

            <p>Welcome to the management dashboard! Here you can create and manage your systems.</p>

            <?php if (count($systems) > 0): ?>
                <h2>Your Systems</h2>
                <ul>
                    <?php foreach ($systems as $system): ?>
                        <li><a href="/manage/system/<?= htmlspecialchars($system['handle']) ?>"><?= htmlspecialchars($system['name']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You don't have any systems yet. <a href="/manage/systems/new">Create your first system</a> to get started!</p>
            <?php endif; ?>
            <!-- <ul>
                <li><a href="/manage/systems/new">Create New System</a></li>
                <!-- Future management links can go here -->
            </ul> -->

            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
</body>