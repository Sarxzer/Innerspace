<?php
/**
 * @var array $parts
 * @var array $current_user
 */
?>
<nav>
    <ul>
        <li><a href="/home">Home</a></li>
        <li><a href="/system">System</a></li>
        <li><a href="/friends">Friends</a></li>
        <li><a href="/dashboard">Dashboard</a></li>
        <?php if (isset($current_user)): ?>
            <li><a href="/settings">Settings</a></li>
            <li><a href="/logout">Logout (<?= htmlspecialchars($current_user['username']) ?>)</a></li>
        <?php else: ?>
            <li><a href="/login">Login</a></li>
        <?php endif; ?>
    </ul>

    <!-- show current url and make each part clickable -->
    <p>Current URL:
        <?php foreach ($parts as $index => $part): ?>
            <a href="/<?= implode('/', array_slice($parts, 0, $index + 1)) ?>"><?= htmlspecialchars($part) ?></a>
            <?php if ($index < count($parts) - 1): ?>
                /
            <?php endif; ?>
        <?php endforeach; ?>
    </p>
</nav>