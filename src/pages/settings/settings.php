<?php

/**
 * @var array $parts
 * @var PDO $pdo
 * @var string $includesDir
 */

include_once __DIR__ . '/../../php/totp.php';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$userId = $_SESSION['user_id'] ?? null;

if (!isset($userId)) {
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
    } elseif (isset($_POST['new_email'], $_POST['password'])) {
        // Handle email update
        $new_email = $_POST['new_email'];
        $password = $_POST['password'];

        // Verify password
        if (password_verify($password, $user['password'])) {
            $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
            $stmt->execute([$new_email, $_SESSION['user_id']]);
            header('Location: /settings');
            exit;
        } else {
            // Handle incorrect password
            echo "Incorrect password.";
        }
    } elseif (isset($_POST['new_password'], $_POST['new_password_confirm'], $_POST['password'])) {
        // Handle password update
        $new_password = $_POST['new_password'];
        $new_password_confirm = $_POST['new_password_confirm'];
        $current_password = $_POST['password'];

        if ($new_password !== $new_password_confirm) {
            echo "New passwords do not match.";
        } elseif (!password_verify($current_password, $user['password'])) {
            echo "Incorrect current password.";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $_SESSION['user_id']]);
            header('Location: /settings');
            exit;
        }
    } elseif (isset($_POST['totp_code']) && $user['totp_enabled']) {
        // Handle 2FA disable
        
        $code = trim($_POST['totp_code']);

        $stmt = $pdo->prepare("SELECT totp_secret FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $secret = $stmt->fetchColumn();

        $ok = totp_verify($secret, $code) || totp_verify_backup($pdo, $_SESSION['user_id'], $code);

        if ($ok) {
            // Disable 2FA in the database and delete all backup codes
            $stmt = $pdo->prepare("UPDATE users SET totp_enabled = 0, totp_secret = NULL WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $stmt = $pdo->prepare("DELETE FROM totp_backup_codes WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            header("Location: /settings");
            exit;
        } else {
            echo "Invalid 2FA code.";
        }

    } elseif (isset($_POST['password']) && !$user['totp_enabled']) {
        // Handle 2FA enable
        
        $password = $_POST['password'];

        if (password_verify($password, $user['password_hash'])) {
            // Generate TOTP secret and save to database
            $data = totp_generate_secret($user['username'], 'Innerspace');

            $_SESSION['pending_totp_user_id'] = $userId;
            $_SESSION['pending_totp_secret'] = $data['secret'];
            $_SESSION['pending_totp_qr'] = $data['qr_base64'];

            header("Location: /settings/setup-totp");
            exit;
        } else {
            echo "Incorrect password.";
        }
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
    <script src="/assets/js/main.js?v=<?= filemtime(__DIR__ . '/../../public/assets/js/main.js') ?>" defer></script>

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
                    <h2>Update Username</h2>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username">

                    <button type="submit">Update Username</button>
                </form>

                <form action="settings" method="POST">
                    <h2>Update Email</h2>
                    <label for="new_email">New Email:</label>
                    <input type="email" id="new_email" name="new_email">

                    <label for="password">Actual Password:</label>
                    <input type="password" id="password" name="password">

                    <button type="submit">Update Email</button>
                </form>

                <form action="settings" method="POST">
                    <h2>Update Password</h2>
                    <label for="password">Actual Password:</label>
                    <input type="password" id="password" name="password">

                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password">

                    <label for="new_password_confirm">Confirm New Password:</label>
                    <input type="password" id="new_password_confirm" name="new_password_confirm">

                    <button type="submit">Update Password</button>
                </form>

                <?php if ($user['totp_enabled']): ?>
                    <form action="settings" method="POST">
                        <h2>Disable 2FA</h2>
                        <label for="totp_code">Current 2FA Code:</label>
                        <input type="text" id="totp_code" name="totp_code">

                        <button type="submit">Disable 2FA</button>
                    </form>
                <?php else: ?>
                    <form action="settings" method="POST">
                        <h2>Enable 2FA</h2>
                        <label for="password">Actual Password:</label>
                        <input type="password" id="password" name="password">

                        <button type="submit">Enable 2FA</button>
                    </form>
                <?php endif; ?>
            </div>

            <?php include $includesDir . '/footer.php'; ?>
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