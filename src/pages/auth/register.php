<?php

/**
 * @var array $parts
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
    $totpEnabled = isset($_POST['totp']);

    if (empty($username) || empty($password)) {
        Alert::error("Username and password are required.");
        header("Location: /register");
        exit;
    }

    $passwordCheck = $auth->passwordMeetsCriteria($password);
    if ($passwordCheck !== true) {
        Alert::error($passwordCheck);
        header("Location: /register");
        exit;
    }

    $auth = new Auth($pdo);
    $userId = $auth->register($username, $password);

    if ($userId === null) {
        Alert::error("Username already taken.");
        header("Location: /register");
        exit;
    }

    // Log the user in
    if ($totpEnabled) {
        $data = totp_generate_secret($username, 'Innerspace');

        $_SESSION['pending_totp_user_id'] = $userId;
        $_SESSION['pending_totp_secret'] = $data['secret'];
        $_SESSION['pending_totp_qr'] = $data['qr_base64'];

        Alert::info("Please scan the QR code with your authenticator app.");

        header("Location: /register/totp");
        exit;
    }
    $_SESSION['user_id'] = $userId;

    Alert::success("Registration successful! Welcome, $username.");

    header("Location: /dashboard");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Innerspace</title>
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
                <form action="register" method="post" class="auth-form">
                    <h1>Register</h1>

                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username" required><br><br>

                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password" required><br><br>

                    <label for="totp">
                        <input type="checkbox" name="totp" id="totp" value="1">
                        Enable Two-Factor Authentication
                    </label><br>

                    <input type="hidden" name="csrf_token" value="<?= Csrf::token() ?>">
                    <input type="submit" value="Register">
                </form>

                <p><a href="/login">Already have an account? Login here.</a></p>
            </div>

            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
    <!-- <?php include $includesDir . '/navbar.php'; ?>

    <h1>Register</h1>
    <form action="register" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Register</button>
    </form> -->
</body>

</html>