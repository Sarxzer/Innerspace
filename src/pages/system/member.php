<?php

/** @var array $parts
 * @var PDO $pdo
 * @var string $includesDir
 * @var string $cssDir
 * @var string $jsDir
 */

$parts ??= explode('/', trim($_SERVER['REQUEST_URI'], '/'));

$system_handle = $parts[1] ?? null;
$member_handle = ltrim($parts[2], '@');

$stmt = $pdo->prepare("SELECT * FROM systems WHERE handle = ?");
$stmt->execute([$system_handle]);
$system = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$system) {
    die("System not found.");
}

$stmt = $pdo->prepare("SELECT * FROM members WHERE system_id = ? AND handle = ?");
$stmt->execute([$system['id'], $member_handle]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {
    die("Member not found.");
}

$memberName = htmlspecialchars($member['name']);
$systemName = htmlspecialchars($system['name']);
$systemHandle = htmlspecialchars($system['handle']);
$pronouns = !empty($member['pronouns']) ? htmlspecialchars($member['pronouns']) : null;

// Build a actually useful description
$description = $pronouns
    ? "{$memberName} ({$pronouns}) is a member of the {$systemName} system on Innerspace."
    : "{$memberName} is a member of the {$systemName} system on Innerspace.";

$canonicalUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($member['name']) ?> | Innerspace</title>
    <link rel="stylesheet" href="<?= $cssDir ?>">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">
    <script src="<?= $jsDir ?>" defer></script>

    <!-- Open Graph -->
    <meta property="og:type" content="profile">
    <meta property="og:site_name" content="Innerspace">
    <meta property="og:title" content="<?= $memberName ?> · <?= $systemName ?>">
    <meta property="og:description" content="<?= $description ?>">
    <meta property="og:url" content="<?= $canonicalUrl ?>">
    <meta property="og:image" content="/assets/icons/icon-512.png">
    <meta property="og:image:alt" content="<?= $memberName ?> on Innerspace">

    <!-- Twitter / X Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?= $memberName ?> · <?= $systemName ?>">
    <meta name="twitter:description" content="<?= $description ?>">
    <meta name="twitter:image" content="/assets/icons/icon-512.png">
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
                <h1><?= htmlspecialchars($member['name']) ?></h1>
                <p>System: <a href='/system/<?= htmlspecialchars($system['handle']) ?>'><?= htmlspecialchars($system['name']) ?></a></p>
                <p>Pronouns: <?= htmlspecialchars($member['pronouns']) ?></p>
                <p>Color: <span style='color: <?= htmlspecialchars($member['color']) ?>'><?= htmlspecialchars($member['color']) ?></span></p>
            </div>

            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
    <!-- <?php include $includesDir . '/navbar.php'; ?>

    <h1><?= htmlspecialchars($member['name']) ?></h1>
    <p>System: <a href='/system/<?= htmlspecialchars($system['handle']) ?>'><?= htmlspecialchars($system['name']) ?></a></p>
    <p>Pronouns: <?= htmlspecialchars($member['pronouns']) ?></p>
    <p>Color: <span style='color: <?= htmlspecialchars($member['color']) ?>'><?= htmlspecialchars($member['color']) ?></span></p> -->
</body>

</html>