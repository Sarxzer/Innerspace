<?php
/**
 * @var array $parts (optional)
 */

require_once __DIR__ . '/../php/alert.php';

$alerts = Alert::getAll();

if (empty($alerts)) {
    return;
}

?>
<div class="alerts-container">
    <?php foreach ($alerts as $alert): ?>
        <div class="alert alert-<?= htmlspecialchars($alert['type']) ?>" role="alert">
            <span class="alert-icon">
                <?php
                    switch ($alert['type']) {
                        case 'success':
                            echo '✓';
                            break;
                        case 'error':
                            echo '✕';
                            break;
                        case 'warning':
                            echo '⚠';
                            break;
                        case 'info':
                            echo 'ℹ';
                            break;
                        case 'dev':
                            echo '⟨/⟩';
                            break;
                        default:
                            echo 'ℹ';
                            break;
                    }
                ?>
            </span>
            <span class="alert-message"><?= htmlspecialchars($alert['message']) ?></span>
        </div>
    <?php endforeach; ?>
</div>
