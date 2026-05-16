<?php
/**
 * @var array $parts
 * @var PDO $pdo
 * @var string $includesDir
 * @var string $cssDir
 * @var string $jsDir
 */

$auth = new Auth($pdo);
$currentUser = $auth->requireCurrentUser();
$currentUserId = (int) $currentUser['id'];

$handle = $parts[2] ?? null;

$stmt = $pdo->prepare('SELECT * FROM systems WHERE handle = ?');
$stmt->execute([$handle]);
$system = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$system) {
    Alert::error("System not found or you don't have permission to manage it.");
    header('Location: /dashboard');
    exit;
}

Guards::requireSystemOwnership($pdo, (int) $system['id']);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $memberHandle = trim($_POST['handle']);
    $memberName = trim($_POST['name']);
    $memberPronouns = trim($_POST['pronouns']);
    $memberRole = trim($_POST['role']);
    $memberColor = trim($_POST['color']);

    if (empty($memberHandle) || empty($memberName)) {
        Alert::error("Member handle and name are required.");
        header('Location: /manage/s/' . htmlspecialchars($handle) . '/new');
        exit;
    }

    if (!preg_match('/^[a-z0-9\-]+$/', $memberHandle)) {
        Alert::error("Member handle can only contain lowercase letters, numbers, and hyphens.");
        header('Location: /manage/s/' . htmlspecialchars($handle) . '/new');
        exit;
    }

    // Check if member handle already exists in this system
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM members WHERE system_id = ? AND handle = ?');
    $stmt->execute([$system['id'], $memberHandle]);
    if ($stmt->fetchColumn() > 0) {
        Alert::error("Member handle already exists in this system. Please choose a different one.");
        header('Location: /manage/s/' . htmlspecialchars($handle) . '/new');
        exit;
    }

    // Insert new member into database
    $stmt = $pdo->prepare('INSERT INTO members (system_id, handle, name, pronouns, role, color) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$system['id'], $memberHandle, $memberName, $memberPronouns, $memberRole, $memberColor]);

    // Redirect to the new member's page
    header('Location: /s/' . htmlspecialchars($handle) . '/@' . htmlspecialchars($memberHandle));
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New System | Innerspace</title>
    <link rel="stylesheet" href="<?= $cssDir ?>">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">
    <script src="<?= $jsDir ?>" defer></script>
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
                <h1>New Member</h1>
                <p>This is the new member page.</p>

                <form action="/manage/s/<?= htmlspecialchars($handle) ?>/new" method="post">
                    <div class="form-group">
                        <label for="handle">Member Handle (lowercase, no spaces):</label>
                        <input type="text" id="handle" name="handle" required pattern="[a-z0-9\-]+">
                    </div>

                    <div class="form-group">
                        <label for="name">Member Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="pronouns">Pronouns:</label>
                        <input type="text" id="pronouns" name="pronouns">
                    </div>

                    <div class="form-group">
                        <label for="role">Role:</label>
                        <input type="text" id="role" name="role">
                    </div>

                    <div class="form-group">
                        <label for="color">Color:</label>
                        <input type="color" id="color" name="color">
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= Csrf::token() ?>">
                    <button type="submit">Create Member</button>
                </form>
            </div>

            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
</body>
</html>
