<?php

/**
 * @var array $parts
 * @var PDO $pdo
 * @var string $includesDir
 */

if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle settings form submission here
    // For example, you could update the user's username in the database
    $new_username = $_POST['username'] ?? '';

    if ($new_username) {
        $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->execute([$new_username, $_SESSION['user_id']]);

        // Optionally update the session username if you store it there
        // $_SESSION['username'] = $new_username;

        // Redirect to avoid form resubmission
        header('Location: /settings');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Innerspace</title>
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= filemtime(__DIR__ . '/../../../public/assets/css/style.css') ?>">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">

</head>

<body>
    <div class="page">
        <div class="pixel-scanlines"></div>
        <div class="content">
            <?php include $includesDir . '/navbar.php'; ?>

            <div class="main">
                <h1>Settings</h1>
                <p>This is the settings page.</p>

                <form action="settings" method="POST">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username">
                    <button type="submit">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
    <!-- <?php include $includesDir . '/navbar.php'; ?>

    <h1>Settings</h1>
    <p>This is the settings page.</p>
    
    <form action="settings" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
        <button type="submit">Save Settings</button>
    </form> -->
</body>

</html>