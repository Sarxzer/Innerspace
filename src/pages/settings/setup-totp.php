<?php
/**
 * @var array $parts
 * @var PDO $pdo
 * @var string $includesDir
 * @var string $cssDir
 * @var string $jsDir
 * @var Alert $alert
 */
require_once __DIR__ . '/../../php/database.php';
require_once __DIR__ . '/../../php/auth.php';
require_once __DIR__ . '/../../php/totp.php';

$userId = $_SESSION['pending_totp_user_id'] ?? null;
$secret = $_SESSION['pending_totp_secret'] ?? null;
$qr     = $_SESSION['pending_totp_qr'] ?? null;

if (!$userId || !$secret) {
    header("Location: /settings");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code'] ?? '');

    if (!totp_verify($secret, $code)) {
        Alert::error("Invalid code. Please try again.");
    } else {
        // Save secret, enable TOTP
        $auth = new Auth($pdo);
        $auth->enableTotp($userId, $secret);

        // Generate backup codes
        $backupCodes = totp_generate_backup_codes($pdo, $userId);

        // Clean up setup session keys, log user in
        unset($_SESSION['pending_totp_user_id'], $_SESSION['pending_totp_secret'], $_SESSION['pending_totp_qr']);
        $_SESSION['user_id'] = $userId;
        $_SESSION['show_backup_codes'] = $backupCodes;

        header("Location: /settings/backup-codes");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set up 2FA | Innerspace</title>
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
                <h1>Set up 2FA</h1>
                <p>Scan this QR code with your authenticator app (Google Authenticator, Aegis, Authy…)</p>

                <img src="data:image/png;base64,<?= $qr ?>" alt="TOTP QR Code"><br><br>

                <details>
                    <summary>Can't scan? Enter manually</summary>
                    <code><?= htmlspecialchars($secret) ?></code>
                </details><br>

                <form action="setup-totp" method="POST" class="auth-form">

                    <label for="code">Enter the 6-digit code from your app:</label><br>
                    <input type="text" id="code" name="code" maxlength="6"
                           placeholder="123456" autocomplete="one-time-code" required><br><br>

                    <input type="hidden" name="csrf_token" value="<?= Csrf::token() ?>">
                    <input type="submit" value="Verify & Enable">
                </form>
            </div>

            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
</body>
</html>