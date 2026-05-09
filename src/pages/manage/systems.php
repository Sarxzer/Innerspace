<?php

/**
 * @var PDO $pdo
 * @var string $includesDir
 */
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

            <ul>
                <li><a href="/manage/systems/new">Create New System</a></li>
                <!-- Future management links can go here -->
            </ul>

            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
</body>