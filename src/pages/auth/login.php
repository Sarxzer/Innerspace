<?php
/**
 * @var PDO $pdo
 * @var string $includesDir
 */
require_once __DIR__ . '/../../php/database.php';
require_once __DIR__ . '/../../php/totp.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        if ($user['totp_enabled']) {
            // Stash user, go ask for TOTP code
            $_SESSION['pending_2fa_user'] = $user['id'];
            header('Location: /login/totp');
            exit;
        } else {
            $_SESSION['user_id'] = $user['id'];
            header('Location: /');
            exit;
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Innerspace</title>
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= filemtime(__DIR__ . '/../../../public/assets/css/style.css') ?>">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">
    <script src="/assets/js/main.js?v=<?= filemtime(__DIR__ . '/../../public/assets/js/main.js') ?>" defer></script>
</head>
<body>
    <div class="page">
        <div class="pixel-scanlines"></div>
        <div class="content">
            <?php include $includesDir . '/navbar.php'; ?>
            <div class="main">
                <form action="login" method="post" class="auth-form">
                    <h1>Login</h1>

                    <?php if (!empty($error)): ?>
                        <p class="error"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>

                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required><br><br>
                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password" required><br><br>
                    <input type="submit" value="Login">
                </form>
                <p><a href="/register">Don't have an account? Register here.</a></p>
            </div>

            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
</body>
</html>