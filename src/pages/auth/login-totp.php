<?php
/**
 * @var PDO $pdo
 * @var string $includesDir
 * @var string $cssDir
 * @var string $jsDir
 * @var Alert $alert
 */
require_once __DIR__ . '/../../php/totp.php';

if (!isset($_SESSION['pending_2fa_user'])) {
    header("Location: /login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    $userId = $_SESSION['pending_2fa_user'];

    $auth = new Auth($pdo);
    $secret = $auth->getTotpSecret($userId);

    if (!$secret || !totp_verify($secret, $code)) {
        Alert::error("Invalid code. Please try again.");
    } else {
        // TOTP verified, log user in
        $auth->login($userId, false);
        unset($_SESSION['pending_2fa_user'], $_SESSION['totp_attempts']);

        Alert::success("Login successful! Welcome back.");
        header("Location: /");
        exit;
    }

    // Increment attempt count, lock out after 5 attempts
    $_SESSION['totp_attempts'] = ($_SESSION['totp_attempts'] ?? 0) + 1;
    if ($_SESSION['totp_attempts'] >= 5) {
        unset($_SESSION['pending_2fa_user'], $_SESSION['totp_attempts']);
        Alert::error("Too many failed attempts. Please log in again.");
        header("Location: /login");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Auth | Innerspace</title>
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
                <form action="totp" method="POST" class="auth-form">
                    <h1>Two-Factor Auth</h1>
                    <p>Enter the 6-digit code from your authenticator app, or a backup code.</p>


                    <label for="code">Code:</label><br>
                    <input type="text" id="code" name="code" maxlength="8"
                           placeholder="123456" autocomplete="one-time-code" required><br><br>

                    <input type="hidden" name="csrf_token" value="<?= Csrf::token() ?>">
                    <input type="submit" value="Verify">
                </form>
                <p><a href="/login">← Back to login</a></p>
            </div>

            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
</body>
</html>