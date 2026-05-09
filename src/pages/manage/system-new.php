<?php
/**
 * @var array $parts
 * @var PDO $pdo
 * @var string $includesDir
 */
// system creation page

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New System | Innerspace</title>
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= filemtime(__DIR__ . '/../../../public/assets/css/style.css') ?>">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">
</head>
<body>
    <div class="page">
        <div class="pixel-scanlines"></div>
        <div class="content">
            <?php include $includesDir . '/navbar.php'; ?>
            <div class="main">
                <h1>Create a new system</h1>
                <p>Welcome to the system creation page!</p>

                <form action="new-system" method="POST">
                    <label for="name">System Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="handle">System Handle (lowercase, no spaces):</label>
                    <div class="input-wrapper">
                        <span class="input-prefix">@</span>
                        <input type="text" id="handle" name="handle" pattern="[a-z0-9\-]+" title="Lowercase letters, numbers, and hyphens only." required>
                    </div>

                    <button type="submit">Create System</button>
                </form>
            </div>

            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
</body>
</html>