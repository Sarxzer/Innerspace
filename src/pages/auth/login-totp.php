<?php
/**
 * @var PDO $pdo
 * @var string $includesDir
 */
require_once __DIR__ . '/../../php/database.php';
require_once __DIR__ . '/../../php/totp.php';

$userId = $_SESSION['pending_2fa_user'] ?? null;
if (!$userId) { header('Location: /login'); exit; }

if (($_SESSION['totp_attempts'] ?? 0) >= 5) {
    $error = "Too many attempts. Please try again later.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $code = trim($_POST['code'] ?? '');

    $stmt = $pdo->prepare("SELECT totp_secret FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $secret = $stmt->fetchColumn();

    $ok = totp_verify($secret, $code) || totp_verify_backup($pdo, $userId, $code);

    if ($ok) {
        unset($_SESSION['pending_2fa_user'], $_SESSION['totp_attempts']);
        $_SESSION['user_id'] = $userId;
        header('Location: /');
        exit;
    } else {
        $_SESSION['totp_attempts'] = ($_SESSION['totp_attempts'] ?? 0) + 1;
        $error = "Invalid code.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Auth | Innerspace</title>
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= filemtime(__DIR__ . '/../../../public/assets/css/style.css') ?>">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">
</head>
<body>
    <div class="page">
        <div class="pixel-scanlines"></div>
        <div class="content">
            <?php include $includesDir . '/navbar.php'; ?>
            <div class="main">
                <form action="totp" method="POST" class="auth-form">
                    <h1>Two-Factor Auth</h1>
                    <p>Enter the 6-digit code from your authenticator app, or a backup code.</p>

                    <?php if (!empty($error)): ?>
                        <p class="error"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>

                    <label for="code">Code:</label><br>
                    <input type="text" id="code" name="code" maxlength="8"
                           placeholder="123456" autocomplete="one-time-code" required><br><br>
                    <input type="submit" value="Verify">
                </form>
                <p><a href="/login">← Back to login</a></p>
            </div>
        </div>
    </div>
</body>
</html>