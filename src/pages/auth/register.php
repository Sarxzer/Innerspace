<?php

/**
 * @var array $parts
 * @var PDO $pdo
 * @var string $includesDir
 * @var string $cssDir
 * @var string $jsDir
 */

require_once __DIR__ . '/../../php/database.php';
require_once __DIR__ . '/../../php/auth.php';
require_once __DIR__ . '/../../php/totp.php';

function passwordMeetsCriteria($password) {
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return "Password must contain at least one uppercase letter.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        return "Password must contain at least one lowercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        return "Password must contain at least one digit.";
    }
    if (!preg_match('/[\W_]/', $password)) {
        return "Password must contain at least one special character.";
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $totpEnabled = isset($_POST['totp']);

    if (empty($username) || empty($password)) {
        die("Username and password are required.");
    }

    $passwordCheck = passwordMeetsCriteria($password);
    if ($passwordCheck !== true) {
        die($passwordCheck);
    }

    $auth = new Auth($pdo);
    $userId = $auth->register($username, $password);

    if ($userId === null) {
        die("Username already taken.");
    }

    // Log the user in
    if ($totpEnabled) {
        $data = totp_generate_secret($username, 'Innerspace');

        $_SESSION['pending_totp_user_id'] = $userId;
        $_SESSION['pending_totp_secret'] = $data['secret'];
        $_SESSION['pending_totp_qr'] = $data['qr_base64'];

        header("Location: /register/totp");
        exit;
    }
    $_SESSION['user_id'] = $userId;

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