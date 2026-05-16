<?php
/**
 * @var PDO $pdo
 * @var string $includesDir
 * @var string $cssDir
 * @var string $jsDir

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
        $auth->login($userId, false);
        unset($_SESSION['pending_2fa_user'], $_SESSION['totp_attempts']);
        Alert::success("Login successful! Welcome back.");
        header("Location: /");
        exit;
    }
    $_SESSION['totp_attempts'] = ($_SESSION['totp_attempts'] ?? 0) + 1;
    if ($_SESSION['totp_attempts'] >= 5) {
        unset($_SESSION['pending_2fa_user'], $_SESSION['totp_attempts']);
        Alert::error("Too many failed attempts. Please log in again.");
        header("Location: /login");
        exit;
    }
}
$attemptsLeft = 5 - ($_SESSION['totp_attempts'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Auth | Innerspace</title>
    <link rel="stylesheet" href="<?= $cssDir ?>">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">
    <script src="<?= $jsDir ?>" defer></script>
    <style>
        .totp-container {
            max-width: 420px;
            margin: 3rem auto;
            padding: 2rem;
            background: #16213e;
            border: 2px solid rgba(163, 196, 243, 0.25);
            box-shadow: 4px 4px 0px #0a0a1a;
            position: relative;
        }

        .totp-container::before {
            content: "";
            position: absolute;
            top: -6px;
            left: -6px;
            right: 6px;
            bottom: 6px;
            border: 1px solid rgba(163, 196, 243, 0.1);
            pointer-events: none;
        }

        .totp-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .totp-icon {
            font-size: 2.5rem;
            display: block;
            margin-bottom: 0.75rem;
            filter: drop-shadow(0 0 8px rgba(163, 196, 243, 0.4));
        }

        .totp-title {
            font-family: "Press Start 2P", monospace;
            font-size: 11px;
            color: #a3c4f3;
            letter-spacing: 1px;
            line-height: 1.8;
            text-shadow: 2px 2px 0 #0a0a1a;
        }

        .totp-subtitle {
            font-family: "VT323", monospace;
            font-size: 18px;
            color: #9d9ab5;
            margin-top: 0.5rem;
        }

        .totp-divider {
            border: none;
            border-top: 1px solid rgba(163, 196, 243, 0.15);
            margin: 1.5rem 0;
        }

        .totp-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .totp-label {
            font-family: "VT323", monospace;
            font-size: 20px;
            color: #9d9ab5;
            display: block;
            margin-bottom: 0.4rem;
            letter-spacing: 1px;
        }

        .totp-code-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid rgba(163, 196, 243, 0.3);
            background: rgba(163, 196, 243, 0.07);
            font-family: "Press Start 2P", monospace;
            font-size: 18px;
            color: #e8e6f0;
            letter-spacing: 0.3em;
            text-align: center;
            outline: none;
            transition: border-color 0.15s, background 0.15s;
            box-sizing: border-box;
        }

        .totp-code-input:focus {
            border-color: #a3c4f3;
            background: rgba(163, 196, 243, 0.12);
        }

        .totp-code-input::placeholder {
            color: rgba(157, 154, 181, 0.4);
            letter-spacing: 0.2em;
        }

        .totp-hint {
            font-family: "VT323", monospace;
            font-size: 16px;
            color: rgba(157, 154, 181, 0.6);
            text-align: center;
        }

        .totp-submit {
            background: #a3c4f3;
            border: none;
            color: #0f3460;
            padding: 0.85rem;
            font-family: "Press Start 2P", monospace;
            font-size: 10px;
            cursor: pointer;
            letter-spacing: 1px;
            transition: background-color 0.15s, transform 0.08s;
            width: 100%;
        }

        .totp-submit:hover {
            background-color: #c3d8f7;
            transform: translate(-1px, -1px);
        }

        .totp-submit:active {
            transform: translate(1px, 1px);
        }

        .totp-attempts {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 0.5rem;
        }

        .attempt-pip {
            width: 8px;
            height: 8px;
            background: #a3f3c4;
            box-shadow: 0 0 4px #a3f3c4;
        }

        .attempt-pip.used {
            background: rgba(157, 154, 181, 0.2);
            box-shadow: none;
        }

        .totp-footer {
            text-align: center;
            margin-top: 1.5rem;
        }

        .totp-footer a {
            font-family: "VT323", monospace;
            font-size: 18px;
            color: #9d9ab5;
            transition: color 0.1s;
        }

        .totp-footer a:hover {
            color: #a3c4f3;
        }

        .backup-hint {
            font-family: "VT323", monospace;
            font-size: 16px;
            color: rgba(157, 154, 181, 0.5);
            text-align: center;
            margin-top: 0.75rem;
        }
    </style>
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
                <div class="totp-container">
                    <div class="totp-header">
                        <span class="totp-icon">🔐</span>
                        <div class="totp-title">Two-Factor Auth</div>
                        <div class="totp-subtitle">Enter code from your authenticator app</div>
                    </div>
                    <hr class="totp-divider">
                    <form action="totp" method="POST" class="totp-form">
                        <div>
                            <label class="totp-label" for="code">// code</label>
                            <input type="text" id="code" name="code" class="totp-code-input" maxlength="8"
                                placeholder="_ _ _ _ _ _" autocomplete="one-time-code" inputmode="numeric" autofocus
                                required>
                            <div class="totp-hint">or enter a backup code</div>
                        </div>
                        <input type="hidden" name="csrf_token" value="<?= Csrf::token() ?>">
                        <button type="submit" class="totp-submit">Verify →</button>
                    </form>
                    <div class="totp-attempts">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <div class="attempt-pip <?= $i >= $attemptsLeft ? 'used' : '' ?>"></div>
                        <?php endfor; ?>
                    </div>
                    <div class="backup-hint"><?= $attemptsLeft ?> attempt<?= $attemptsLeft !== 1 ? 's' : '' ?> remaining
                    </div>
                    <div class="totp-footer">
                        <a href="/login">← back to login</a>
                    </div>
                </div>
            </div>
            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
</body>

</html>