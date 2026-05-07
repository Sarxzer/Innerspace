<?php
/**
 * @var string $includesDir
 * 
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innerspace</title>
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= filemtime(__DIR__ . '/../../../public/assets/css/style.css') ?>">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">

</head>

<body>
    <div class="page">
        <div class="pixel-scanlines"></div>
        <div class="content">
            <?php include $includesDir . '/navbar.php'; ?>

            <h1>Innerspace</h1>

            <p>Welcome to Innerspace! This is a platform for managing and sharing information about systems, members, and fronting sessions.</p>

            <h2>Getting Started</h2>

            <p>To get started, please log in or create an account. Once you have an account, you can create your own system, add members, and track fronting sessions.</p>  
        </div>
    </div>
    <!-- <h1>Innerspace</h1>
    <p>Welcome to Innerspace! This is a platform for managing and sharing information about systems, members, and fronting sessions.</p>

    <h2>Getting Started</h2>
    <p>To get started, please log in or create an account. Once you have an account, you can create your own system, add members, and track fronting sessions.</p> -->

</body>

</html>