<?php
/**
 * @var string $includesDir
 * @var string $cssDir
 * @var string $jsDir
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link rel="stylesheet" href="<?= $cssDir ?>">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">
    <script src="<?= $jsDir?>" defer></script>
</head>

<body>
    <div class="page">
        <div class="pixel-scanlines"></div>
        <div class="content">
            <?php include $includesDir . '/navbar.php'; ?>
            <div class="alerts-wrapper">
                <?php include $includesDir . '/alerts.php'; ?>
            </div>

            <h1 style="text-align: center; font-size: larger;">404 - Page Not Found</h1>
            <p style="text-align: center;">The page you are looking for does not exist.</p>
            <img src="https://beurreland.cc/assets/img/davide-jambon-beuere.gif" alt="" style="margin: 30px;">

            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
</body>

</html>