<?php
/**
 * @var array $parts
 * @var array $current_user
 * @var array $breadcrumbs
 */
?>
<nav>
    <div class="nav-inner">
        <div class="nav-logo">✦ INNER<br>SPACE</div>
        <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
    <div class="nav-links">
        <a href="/home" class="<?php echo ($parts[0] === 'home' || $parts[0] === '') ? 'active' : ''; ?>">Home</a>
        <a href="/system" class="<?php echo ($parts[0] === 'system') ? 'active' : ''; ?>">System</a>
        <a href="/friends" class="<?php echo ($parts[0] === 'friends') ? 'active' : ''; ?>">Friends</a>
        <a href="/dashboard" class="<?php echo ($parts[0] === 'dashboard') ? 'active' : ''; ?>">Dashboard</a>
        <a href="/manage" class="<?php echo ($parts[0] === 'manage') ? 'active' : ''; ?>">Manage</a>
        <?php if (isset($current_user)): ?>
            <a href="/settings" class="<?php echo ($parts[0] === 'settings') ? 'active' : ''; ?>">Settings</a>
            <a href="/logout">Logout (<?= htmlspecialchars($current_user['username']) ?>)</a>
        <?php else: ?>
            <a href="/login" class="<?php echo ($parts[0] === 'login') ? 'active' : ''; ?>">Login</a>
        <?php endif; ?>
    </div>
</nav>
<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $crumb): ?>
        / <a href="<?= $crumb['url'] ?>"><?= $crumb['name'] ?></a>
    <?php endforeach; ?>
</div>