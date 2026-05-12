<?php
/**
 * @var PDO $pdo
 * @var string $includesDir
 * @var string $cssDir
 * @var string $jsDir
 * @var Alert $alert
 */
require_once __DIR__ . '/../../php/totp.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $auth = new Auth($pdo);
    $userId = $auth->checkCredentials($username, $password);

    if ($userId !== null) {
        // Check if user has TOTP enabled
        if ($auth->hasTotpEnabled($userId)) {
            // Require TOTP verification
            $auth->loginWithTwoFactor($userId);
            header('Location: /login/totp');
            exit;
        }

        // No TOTP, proceed with regular login
        $remember = isset($_POST['remember']);
        $auth->login($userId, $remember);

        $alert->success("Login successful! Welcome back.");
        header('Location: /');
        exit;
    } else {
        $alert->error("Invalid username or password.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Innerspace</title>
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
            <div class="main">
                <form action="login" method="post" class="auth-form">
                    <h1>Login</h1>


                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required><br><br>
                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password" required><br><br>

                    <label for="remember">
                        <input type="checkbox" id="remember" name="remember"> Remember me
                    </label>
                    <input type="submit" value="Login">
                </form>
                <p><a href="/register">Don't have an account? Register here.</a></p>
            </div>

            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
</body>
</html>