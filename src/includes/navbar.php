<?php

/**
 * @var array $parts
 * @var array $current_user
 */
?>
<nav>
    <div class="nav-inner">
        <div class="nav-logo">✦ INNER<br>SPACE</div>
        <a href="/home" class="<?php echo ($parts[0] === 'home' || $parts[0] === '') ? 'active' : ''; ?>">Home</a>
        <a href="/system" class="<?php echo ($parts[0] === 'system') ? 'active' : ''; ?>">System</a>
        <a href="/friends" class="<?php echo ($parts[0] === 'friends') ? 'active' : ''; ?>">Friends</a>
        <a href="/dashboard" class="<?php echo ($parts[0] === 'dashboard') ? 'active' : ''; ?>">Dashboard</a>
        <?php if (isset($current_user)): ?>
            <a href="/settings" class="<?php echo ($parts[0] === 'settings') ? 'active' : ''; ?>">Settings</a>
            <a href="/logout">Logout (<?= htmlspecialchars($current_user['username']) ?>)</a>
        <?php else: ?>
            <a href="/login " class="<?php echo ($parts[0] === 'login') ? 'active' : ''; ?>">Login</a>
        <?php endif; ?>
    </div>
</nav>
<div class="breadcrumb">
    <a href="/home">Home</a>
    <?php if ($parts[0] !== 'home' && $parts[0] !== ''): ?>
        <?php foreach ($parts as $index => $part): ?>
            <span>/</span>
            <a href="/<?php echo implode('/', array_slice($parts, 0, $index + 1)); ?>"><?php echo ucfirst(htmlspecialchars($part)); ?></a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>