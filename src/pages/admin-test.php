<?php
/**
 * @var array $parts
 * @var PDO $pdo
 * @var string $includesDir
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once __DIR__ . '/../../src/php/database.php';

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 1;

// Fetch system
$stmt = $pdo->prepare("SELECT * FROM systems WHERE user_id = ?");
$stmt->execute([$user_id]);
$system = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$system) {
    die("No system found for user ID $user_id.");
}

$system_id = $system['id'];

$stmt = $pdo->prepare("SELECT * FROM `systems`");
$stmt->execute();
$systems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch members
$stmt = $pdo->prepare("SELECT * FROM members WHERE system_id = ?");
$stmt->execute([$system_id]);
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch current fronters (no ended_at)
$stmt = $pdo->prepare("
    SELECT m.name, m.color, m.pronouns, fs.note
    FROM fronting_session_members fsm
    JOIN fronting_sessions fs ON fs.id = fsm.session_id
    JOIN members m ON m.id = fsm.member_id
    WHERE fs.system_id = ? AND fs.ended_at IS NULL
");
$stmt->execute([$system_id]);
$fronters = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch fronting history
$stmt = $pdo->prepare("
    SELECT fs.started_at, fs.ended_at, fs.note,
           GROUP_CONCAT(m.name SEPARATOR ', ') AS members_out
    FROM fronting_sessions fs
    JOIN fronting_session_members fsm ON fsm.session_id = fs.id
    JOIN members m ON m.id = fsm.member_id
    WHERE fs.system_id = ?
    GROUP BY fs.id
    ORDER BY fs.started_at DESC
    LIMIT 10
");
$stmt->execute([$system_id]);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch friends
$stmt = $pdo->prepare("
    SELECT u.username, f.access_level
    FROM friends f
    JOIN users u ON u.id = f.friend_user_id
    WHERE f.system_id = ?
");
$stmt->execute([$system_id]);
$friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($system['name']) ?> | Innerspace</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">

    <style>
        .color-dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 6px;
            vertical-align: middle;
        }
        .member {
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 8px;
            text-align: left;
        }
        th {
            background: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="pixel-scanlines"></div>
        <div class="content">
            <?php include $includesDir . '/navbar.php'; ?>

            <div class="main">
                <h1><?= htmlspecialchars($system['name']) ?></h1>
                <p><?= htmlspecialchars($system['description']) ?></p>
                <p><strong>Public:</strong> <?= $system['is_public'] ? 'Yes' : 'No' ?></p>

                <h2>Currently Fronting (<?= $fronters[0]['note'] ?? 'No note' ?>)</h2>
                <?php if ($fronters): ?>
                    <?php foreach ($fronters as $f): ?>
                        <p>
                            <span class="color-dot" style="background:<?= htmlspecialchars($f['color']) ?>"></span>
                            <strong><?= htmlspecialchars($f['name']) ?></strong>
                            (<?= htmlspecialchars($f['pronouns']) ?>)
                        </p>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nobody is currently fronting.</p>
                <?php endif; ?>

                <h2>Members</h2>
                <?php foreach ($members as $m): ?>
                    <div class="member">
                        <span class="color-dot" style="background:<?= htmlspecialchars($m['color']) ?>"></span>
                        <strong><?= htmlspecialchars($m['name']) ?></strong>
                        (<?= htmlspecialchars($m['pronouns']) ?>) — <em><?= htmlspecialchars($m['role']) ?></em>
                        <p><?= htmlspecialchars($m['description']) ?></p>
                        <small>Visibility: <?= htmlspecialchars($m['visibility']) ?></small>
                        <?php if ($m['avatar_url']): ?>
                            <div><img src="<?= htmlspecialchars($m['avatar_url']) ?>" alt="Avatar" width="80"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <h2>Fronting History</h2>
                <?php if ($history): ?>
                    <table>
                        <tr>
                            <th>Who</th>
                            <th>Started</th>
                            <th>Ended</th>
                            <th>Note</th>
                        </tr>
                        <?php foreach ($history as $h): ?>
                            <tr>
                                <td><?= htmlspecialchars($h['members_out']) ?></td>
                                <td><?= htmlspecialchars($h['started_at']) ?></td>
                                <td><?= $h['ended_at'] ? htmlspecialchars($h['ended_at']) : '<em>going</em>' ?></td>
                                <td><?= htmlspecialchars($h['note'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No history yet.</p>
                <?php endif; ?>
                <h2>Friends</h2>
                <?php if ($friends): ?>
                    <table>
                        <tr>
                            <th>Username</th>
                            <th>Access</th>
                        </tr>
                        <?php foreach ($friends as $fr): ?>
                            <tr>
                                <td><?= htmlspecialchars($fr['username']) ?></td>
                                <td><?= htmlspecialchars($fr['access_level']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No friends added yet.</p>
                <?php endif; ?>
    </div>

    <!-- <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="goto">
        <?php foreach ($systems as $s): ?>
            <a href="?user_id=<?= $s['user_id'] ?>"><?= htmlspecialchars($s['name']) ?></a>
        <?php endforeach; ?>
    </div>

    <h1><?= htmlspecialchars($system['name']) ?> | Innerspace</h1>
    <p><?= htmlspecialchars($system['description']) ?></p>
    <p><strong>Public:</strong> <?= $system['is_public'] ? 'Yes' : 'No' ?></p>

    <h2>Currently Fronting (<?= $fronters[0]['note'] ?? 'No note' ?>)</h2>
    <?php if ($fronters): ?>
        <?php foreach ($fronters as $f): ?>
            <p>
                <span class="color-dot" style="background:<?= htmlspecialchars($f['color']) ?>"></span>
                <strong><?= htmlspecialchars($f['name']) ?></strong>
                (<?= htmlspecialchars($f['pronouns']) ?>)
            </p>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nobody is currently fronting.</p>
    <?php endif; ?>

    <h2>Members</h2>
    <?php foreach ($members as $m): ?>
        <div class="member">
            <span class="color-dot" style="background:<?= htmlspecialchars($m['color']) ?>"></span>
            <strong><?= htmlspecialchars($m['name']) ?></strong>
            (<?= htmlspecialchars($m['pronouns']) ?>) — <em><?= htmlspecialchars($m['role']) ?></em>
            <p><?= htmlspecialchars($m['description']) ?></p>
            <small>Visibility: <?= htmlspecialchars($m['visibility']) ?></small>
            <?php if ($m['avatar_url']): ?>
                <div><img src="<?= htmlspecialchars($m['avatar_url']) ?>" alt="Avatar" width="80"></div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <h2>Fronting History</h2>
    <?php if ($history): ?>
        <table>
            <tr>
                <th>Who</th>
                <th>Started</th>
                <th>Ended</th>
                <th>Note</th>
            </tr>
            <?php foreach ($history as $h): ?>
                <tr>
                    <td><?= htmlspecialchars($h['members_out']) ?></td>
                    <td><?= htmlspecialchars($h['started_at']) ?></td>
                    <td><?= $h['ended_at'] ? htmlspecialchars($h['ended_at']) : '<em>going</em>' ?></td>
                    <td><?= htmlspecialchars($h['note'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No history yet.</p>
    <?php endif; ?>

    <h2>Friends</h2>
    <?php if ($friends): ?>
        <table>
            <tr>
                <th>Username</th>
                <th>Access</th>
            </tr>
            <?php foreach ($friends as $fr): ?>
                <tr>
                    <td><?= htmlspecialchars($fr['username']) ?></td>
                    <td><?= htmlspecialchars($fr['access_level']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No friends added yet.</p>
    <?php endif; ?> -->

</body>

</html>