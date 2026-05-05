<?php
/**
 * @var array $parts
 * @var PDO $pdo
 * @var string $includesDir
 */

require_once __DIR__ . '/../../php/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        die("Username and password are required.");
    }

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        die("Username already taken.");
    }

    // Create new user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $stmt->execute([$username, $hashed_password]);

    // Log the user in
    $_SESSION['user_id'] = $pdo->lastInsertId();
    
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
</head>
<body>
    <?php include $includesDir . '/navbar.php'; ?>

    <h1>Register</h1>
    <form action="register" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">Register</button>
    </form>
</body>
</html>